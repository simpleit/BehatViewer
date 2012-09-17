<?php
namespace BehatViewer\BehatViewerWorkerBundle\Builder;

use
    Symfony\Component\DependencyInjection\ContainerAware,
    BehatViewer\BehatViewerCoreBundle\Entity,
    Symfony\Component\Console\Output\OutputInterface
;

abstract class Builder extends ContainerAware implements BuilderInterface
{
    /**
     * @return int
     */
    public function build(Entity\Strategy $strategy, OutputInterface $output)
    {
        if (false === $this->supports($strategy)) {
            throw new \RuntimeException(
                sprintf(
                    'Strategy %s is not supported by builder %s',
                    get_class($strategy),
                    __CLASS__
                )
            );
        }

        $output->writeln(
            sprintf(
                '<info>Starting builder</info> <comment>%s</comment>',
                get_class($this)
            )
        );

        return 0;
    }

    /**
     * @return bool
     */
    abstract protected function supports(Entity\Strategy $strategy);
}
