<?php
namespace BehatViewer\BehatViewerWorkerBundle\Strategy;

use
    Symfony\Component\DependencyInjection\ContainerAware,
    BehatViewer\BehatViewerBundle\Entity,
    BehatViewer\BehatViewerWorkerBundle\Strategy\Configuration,
    Symfony\Component\Console\Output\ConsoleOutput
;

abstract class Strategy extends ContainerAware implements StrategyInterface
{
    /** @var \BehatViewer\BehatViewerBundle\Entity\Project */
    private $project;

    /** @var \Symfony\Component\Console\Output\ConsoleOutput */
    private $output;

    /** @var \BehatViewer\BehatViewerBundle\Strategy\Configuration\Configuration */
    protected $configuration;

    /**
     * @abstract
     *
     * @return int
     */
    public function build()
    {
        $output = $this->getOutput();
        $self = $this;

        $output->writeln(
            call_user_func(function() use ($self) {
                $data = json_decode($self->getConfiguration()->getData(), true);
                $separator = str_pad('', 50, '-');
                $msgs = array(
                    $separator,
                    sprintf('<info>Starting strategy </info><comment>%s</comment>', get_class($self))
                );

                foreach ($data as $k => $v) {
                    $msgs[] = sprintf('<info>%s</info> : %s', $k, $v);
                }

                $msgs[] = $separator;

                return $msgs;
            })
        );
    }

    public function setOutput(ConsoleOutput $output)
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

    /**
     * @param \BehatViewer\BehatViewerBundle\Entity\Project $project
     *
     * @return Strategy
     */
    public function setProject(Entity\Project $project)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * @return \BehatViewer\BehatViewerBundle\Entity\Project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * @param \BehatViewer\BehatViewerBundle\Strategy\Configuration\Configuration $config
     *
     * @return Strategy
     */
    public function setConfiguration(Configuration\Configuration $config)
    {
        $this->configuration = $config;

        return $this;
    }

    /**
     * @return \BehatViewer\BehatViewerBundle\Strategy\Configuration\Configuration
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getId();
    }
}
