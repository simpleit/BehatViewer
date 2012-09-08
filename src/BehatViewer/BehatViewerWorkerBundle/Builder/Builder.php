<?php
namespace BehatViewer\BehatViewerWorkerBundle\Builder;

use
    Symfony\Component\DependencyInjection\ContainerAware,
    BehatViewer\BehatViewerBundle\Entity,
    Symfony\Component\Console\Output\ConsoleOutput
;

abstract class Builder extends ContainerAware implements BuilderInterface
{
    /** @var \Symfony\Component\Console\Output\ConsoleOutput */
    private $output;

    public function __construct(ConsoleOutput $output = null)
    {
        $this->setOutput($output);
    }

    /**
     * @abstract
     *
     * @return int
     */
    public function build(Entity\Strategy $strategy)
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

        $this->getOutput()->writeln(
            sprintf(
                '<info>Starting builder</info> <comment>%s</comment>',
                get_class($this)
            )
        );

        return 0;
    }

    /**
     * @abstract
     *
     * @return bool
     */
    abstract protected function supports(Entity\Strategy $strategy);

    public function setOutput(ConsoleOutput $output = null)
    {
        $this->output = $output;

        return $this;
    }

    public function getOutput()
    {
        if (null === $this->output) {
            $this->output = new ConsoleOutput();
        }

        return $this->output;
    }
}
