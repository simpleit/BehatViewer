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

        $path = $this->getConfiguration()->getPath();
        $file = $this->getBuildScript();
        $output = $this->getOutput();

        $vagrant = new \BehatViewer\BehatViewerWorkerBundle\Script\VagrantScript($file);
        $process = new \BehatViewer\BehatViewerBundle\Process\UnbefferedProcess(
            (string) $vagrant,
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

        $analyzer = $this->container->get('behat_viewer.analyzer');
        $data = json_decode(file_get_contents($path . DIRECTORY_SEPARATOR . 'behat-viewer.json'), true);
        $analyzer->analyze($this->getProject(), $data);

        return $status;
    }

    protected function getBuildScript()
    {
        $cmd = $this->getProject()->getTestCommand();
        $cmd = str_replace("\r\n", PHP_EOL, $cmd);
        $path = $this->getConfiguration()->getPath();

        $file = uniqid() . '.sh';
        $filepath = $path . DIRECTORY_SEPARATOR . $file;
        $fp = fopen($filepath, 'w+');

        $build = new \BehatViewer\BehatViewerWorkerBundle\Script\Script();
        $build->append(new \BehatViewer\BehatViewerWorkerBundle\Script\XvfbScript());
        $build->append(new \BehatViewer\BehatViewerWorkerBundle\Script\SahiScript());
        $build->append(new \BehatViewer\BehatViewerWorkerBundle\Script\SeleniumScript());

        $script = '#!/bin/sh' . PHP_EOL . $build . PHP_EOL . $cmd;

        fwrite($fp, $script, strlen($script));
        fclose($fp);

        return $file;
    }
}
