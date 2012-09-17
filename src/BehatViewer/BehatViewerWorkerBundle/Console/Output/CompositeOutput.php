<?php
namespace BehatViewer\BehatViewerWorkerBundle\Console\Output;

use Symfony\Component\Console\Output\OutputInterface,
    Symfony\Component\Console\Formatter\OutputFormatterInterface;

class CompositeOutput implements OutputInterface
{
    private $outputs;
    private $verbosity;
    private $decorated = true;
    private $formatter;

    public function __construct(array $outputs = array())
    {
        foreach ($outputs as $output) {
            $this->addOutput($output);
        }
    }

    public function addOutput(OutputInterface $output)
    {
        $this->outputs[] = $output;

        return $this;
    }

    public function write($messages, $newline = false, $type = 0)
    {
        foreach ($this->outputs as $output) {
            $output->write($messages, $newline, $type);
        }
    }

    public function writeln($messages, $type = 0)
    {
        foreach ($this->outputs as $output) {
            $output->writeln($messages, $type);
        }
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
        return $this->formatter;
    }
}
