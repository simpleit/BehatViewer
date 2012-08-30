<?php
namespace BehatViewer\BehatViewerWorkerBundle\Strategy;

use
    BehatViewer\BehatViewerWorkerBundle\Strategy\Form\Type\GitStrategyType
;

class GitStrategy extends Strategy
{
    public static function getId()
    {
        return 'git';
    }

    public static function getLabel()
    {
        return 'Git repository';
    }

    public static function getForm()
    {
        return new GitStrategyType();
    }

    public static function getNewConfiguration()
    {
        return new Configuration\GitStrategyConfiguration();
    }

    protected function getRepositoryUrl()
    {
        return $this->getConfiguration()->getRepositoryUrl();
    }

    protected function getCloneCommand()
    {
        return 'git clone --depth=50 ' . $this->getRepositoryUrl();
    }

    protected function getClonePath()
    {
        return sprintf(
            $this->container->get('kernel')->getRootDir() . '/data/repos/%s/%s',
            $this->getProject()->getUser()->getUsername(),
            $this->getProject()->getSlug()
        );
    }

    protected function cloneRepository()
    {
        $output = $this->getOutput();
        $dir = $this->getClonePath();

        if (false === is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        $process = new \BehatViewer\BehatViewerBundle\Process\UnbefferedProcess(
            $this->getCloneCommand(),
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

        $this->checkoutBranch();

        if ($status !== 0) {
            $process = new \BehatViewer\BehatViewerBundle\Process\UnbefferedProcess(
                'git reset --hard; git pull origin ' . $this->getConfiguration()->getBranch(),
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

    public function checkoutBranch()
    {
        $output = $this->getOutput();
        $process = new \BehatViewer\BehatViewerBundle\Process\UnbefferedProcess(
            sprintf(
                'git checkout %s',
                $this->getConfiguration()->getBranch()
            ),
            $this->getClonePath()
        );

        return $process->run(function ($type, $buffer) use (&$output) {
            if ('err' === $type) {
                $output->writeln('<error>' . $buffer . '</error>');
            } else {
                $output->write($buffer);
            }
        });
    }

    public function build()
    {
        parent::build();

        $this->cloneRepository();

        $configuration = new Configuration\LocalStrategyConfiguration();
        $configuration->setProject($this->getProject());
        $configuration->setPath($this->getClonePath());

        $strategy = new LocalStrategy();
        $strategy->setConfiguration($configuration);
        $strategy->setProject($this->getProject());
        $strategy->setContainer($this->container);

        return $strategy->build();
    }
}
