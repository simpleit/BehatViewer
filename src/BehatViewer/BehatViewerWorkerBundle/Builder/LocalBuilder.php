<?php
namespace BehatViewer\BehatViewerWorkerBundle\Builder;

use
    Symfony\Component\Console\Output\ConsoleOutput,
    BehatViewer\BehatViewerBundle\Entity
;
use Symfony\Component\Console\Output\OutputInterface;

class LocalBuilder extends Builder
{
    public function build(Entity\Strategy $strategy, OutputInterface $output)
    {
        parent::build($strategy, $output);

        $path = $strategy->getPath();
        $file = $this->getBuildScript($strategy);

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
        $analyzer->analyze($strategy->getProject(), $data);

        return $status;
    }

    protected function supports(Entity\Strategy $strategy)
    {
        return ($strategy instanceof Entity\LocalStrategy);
    }

    protected function getBuildScript(Entity\LocalStrategy $strategy)
    {
        $cmd = $strategy->getProject()->getTestCommand();
        $cmd = str_replace("\r\n", PHP_EOL, $cmd);
        $path = $strategy->getPath();

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
