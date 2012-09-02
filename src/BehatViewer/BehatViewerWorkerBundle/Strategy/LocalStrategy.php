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

		$file = uniqid() . '.sh';
		$filepath = $path . DIRECTORY_SEPARATOR . $file;
        $fp = fopen($filepath, 'w+');

		$script = new \BehatViewer\BehatViewerWorkerBundle\Script\Script();
		$script->append(new \BehatViewer\BehatViewerWorkerBundle\Script\XvfbScript());
		$script->append(new \BehatViewer\BehatViewerWorkerBundle\Script\SahiScript());
		$script->append(new \BehatViewer\BehatViewerWorkerBundle\Script\SeleniumScript());

		$script = '#!/bin/sh' . PHP_EOL . $script . PHP_EOL . $cmd;

        fwrite($fp, $script, strlen($script));
        fclose($fp);

        $output = new ConsoleOutput();
        $process = new \BehatViewer\BehatViewerBundle\Process\UnbefferedProcess(
            'vagrant up' . PHP_EOL . 'vagrant ssh -c "cd /vagrant; sudo sh -e ./' . $file . '"' . PHP_EOL . 'vagrant halt',  //. PHP_EOL . 'vagrant destroy -f',
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

        if (file_exists($path)) {
            //unlink($path);
        }

        return $status;
    }
}
