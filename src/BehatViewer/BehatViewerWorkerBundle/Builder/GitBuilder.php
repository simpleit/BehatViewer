<?php
namespace BehatViewer\BehatViewerWorkerBundle\Builder;

use BehatViewer\BehatViewerBundle\Entity;
use Symfony\Component\Console\Output\OutputInterface;

class GitBuilder extends Builder
{
    protected function getRepositoryUrl(Entity\Strategy $strategy)
    {
        return $strategy->getUrl();
    }

    protected function getCloneCommand(Entity\Strategy $strategy)
    {
        return 'git clone --depth=50 ' . $this->getRepositoryUrl($strategy);
    }

    protected function getClonePath(\BehatViewer\BehatViewerBundle\Entity\Strategy $strategy)
    {
        return sprintf(
            $this->container->get('kernel')->getRootDir() . '/data/repos/%s/%s',
            $strategy->getProject()->getUser()->getUsername(),
            $strategy->getProject()->getSlug()
        );
    }

    protected function cloneRepository(Entity\Strategy $strategy, OutputInterface $output)
    {
        $dir = $this->getClonePath($strategy);

        if (false === is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        $process = new \BehatViewer\BehatViewerBundle\Process\UnbefferedProcess(
            $this->getCloneCommand($strategy),
            realpath($dir . DIRECTORY_SEPARATOR . '..')
        );

        $process->setTimeout(600);

        $status = $process->run(function ($type, $buffer) use (&$output) {
            if ('err' === $type) {
                $output->writeln('<error>' . $buffer . '</error>');
            } else {
                $output->write($buffer);
            }
        });

        $this->checkoutBranch($strategy, $output);

        if ($status !== 0) {
            $process = new \BehatViewer\BehatViewerBundle\Process\UnbefferedProcess(
                'git fetch origin; git reset --hard origin/' . $strategy->getBranch() . '; git pull origin',
                $dir
            );
            $status = $process->run(function ($type, $buffer) use (&$output) {
                if ('err' === $type) {
                    $output->writeln('<error>' . $buffer . '</error>');
                } else {
                    $output->write($buffer);
                }
            });
        }

        return $status;
    }

    protected function checkoutBranch(Entity\Strategy $strategy, OutputInterface $output)
    {
        $process = new \BehatViewer\BehatViewerBundle\Process\UnbefferedProcess(
            sprintf(
                'git checkout %s',
                $strategy->getBranch()
            ),
            $this->getClonePath($strategy)
        );

        return $process->run(function ($type, $buffer) use (&$output) {
            if ('err' === $type) {
                $output->writeln('<error>' . $buffer . '</error>');
            } else {
                $output->write($buffer);
            }
        });
    }

    public function build(Entity\Strategy $strategy, OutputInterface $output)
    {
        parent::build($strategy, $output);

        $this->cloneRepository($strategy, $output);

        $local = new Entity\LocalStrategy();
        $local->setProject($strategy->getProject());
        $local->setPath($this->getClonePath($strategy));

        return $this->container->get('behat_viewer.builder')->build($local, $output);
    }

    protected function supports(Entity\Strategy $strategy)
    {
        return ($strategy instanceof Entity\GitStrategy);
    }
}
