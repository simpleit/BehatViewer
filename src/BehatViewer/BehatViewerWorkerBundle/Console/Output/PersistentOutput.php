<?php
namespace BehatViewer\BehatViewerWorkerBundle\Console\Output;

use Symfony\Component\Console\Output\OutputInterface,
    Symfony\Component\Console\Formatter\OutputFormatterInterface;
use BehatViewer\BehatViewerPusherBundle\Console\Formatter\PusherFormater;
use Symfony\Component\DependencyInjection\ContainerInterface;
use BehatViewer\BehatViewerWorkerBundle\Entity\Job;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

class PersistentOutput implements OutputInterface, ContainerAwareInterface
{
    private $job;
    private $verbosity;
    private $decorated = true;
    private $formatter;
    protected $container;

    public function __construct(Job $job)
    {
        $this->job = $job;
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function getJob()
    {
        return $this->job;
    }

    public function write($messages, $newline = false, $type = 0)
    {
        $this->job->addLog($this->getFormatter()->format($messages));
        $this->container->get('doctrine')->getManager()->persist($this->job);
        $this->container->get('doctrine')->getManager()->flush();
    }

    public function writeln($messages, $type = 0)
    {
        $this->write($messages, false, $type);
    }

    public function setVerbosity($level)
    {
        $this->verbosity = $level;
    }

    public function getVerbosity()
    {
        return $this->verbosity;
    }

    public function setDecorated($decorated)
    {
        $this->decorated = $decorated;

        return $this;
    }

    public function isDecorated()
    {
        return $this->decorated;
    }

    public function setFormatter(OutputFormatterInterface $formatter)
    {
        $this->formatter = $formatter;

        return $this;
    }

    public function getFormatter()
    {
        if (null === $this->formatter) {
            $this->setFormatter(new PusherFormater());
        }

        return $this->formatter;
    }
}
