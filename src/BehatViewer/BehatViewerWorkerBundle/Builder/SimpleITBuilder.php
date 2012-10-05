<?php
namespace BehatViewer\BehatViewerWorkerBundle\Builder;

use
    Symfony\Component\Console\Output\ConsoleOutput,
    BehatViewer\BehatViewerCoreBundle\Entity
;
use Symfony\Component\Console\Output\OutputInterface;

class SimpleITBuilder extends Builder
{
    public function build(Entity\Strategy $strategy, OutputInterface $output)
    {

        $path = sprintf(
            $this->container->get('kernel')->getRootDir() . '/data/repos/%s',
            $strategy->getProject()->getSlug()
        );

        if(!file_exists($path))
        {
            mkdir($path, 777);
        }

		if(file_exists($path . DIRECTORY_SEPARATOR . 'behat-viewer.json'))
        {
			$analyzer = $this->container->get('behat_viewer.analyzer');
			$data = json_decode(file_get_contents($path . DIRECTORY_SEPARATOR . 'behat-viewer.json'), true);
			$analyzer->analyze($strategy->getProject(), $data);
            unlink($path . DIRECTORY_SEPARATOR . 'behat-viewer.json');
		}
        else
       {
			$output->write('<error>There was an error will running the build script or the report was not found</error>');
		}

        return 1;
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
