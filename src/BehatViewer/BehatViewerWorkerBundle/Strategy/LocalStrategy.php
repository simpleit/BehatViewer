<?php
namespace BehatViewer\BehatViewerWorkerBundle\Strategy;

use
    BehatViewer\BehatViewerWorkerBundle\Strategy\Form\Type\LocalStrategyType,
    Symfony\Component\Console\Output\ConsoleOutput
;

class LocalStrategy extends Strategy
{
    public static function getId()
    {
        return 'local';
    }

    public static function getLabel()
    {
        return 'Local directory';
    }

    public static function getForm()
    {
        return new LocalStrategyType();
    }

    public static function getNewConfiguration()
    {
        return new Configuration\LocalStrategyConfiguration();
    }

    public function build()
    {
        parent::build();

        $project = $this->getProject();

        $cmd = $project->getTestCommand();
        $cmd = str_replace("\r\n", PHP_EOL, $cmd);
        $path = $this->getConfiguration()->getPath();

        if (file_exists($path . DIRECTORY_SEPARATOR . 'build.sh')) {
            unlink($path . DIRECTORY_SEPARATOR . 'build.sh');
        }
        $fp = fopen($path . DIRECTORY_SEPARATOR . 'build.sh', 'w+');
        $script = '#!/bin/sh' . PHP_EOL . $cmd;
        fwrite($fp, $script, strlen($script));
        fclose($fp);

        $output = new ConsoleOutput();
        $process = new \BehatViewer\BehatViewerBundle\Process\UnbefferedProcess(
            'vagrant up' . PHP_EOL . 'vagrant ssh -c "cd /vagrant; sh -e ./build.sh"' . PHP_EOL . 'vagrant halt' . PHP_EOL . 'vagrant destroy -f',
            $path
        );
        $process->setTimeout(600);
        $status = $process->run(function ($type, $buffer) use (&$output) {
            if ('err' === $type) {
                $output->write('<error>' . $buffer . '</error>');
            } else {
                $output->write($buffer);
            }
        });

        if (file_exists('build.sh')) {
            unlink('build.sh');
        }

        return $status;
    }
}
