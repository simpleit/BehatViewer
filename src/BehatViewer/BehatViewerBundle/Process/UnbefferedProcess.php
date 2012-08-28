<?php
namespace BehatViewer\BehatViewerBundle\Process;

use Symfony\Component\Process\Process;

class UnbefferedProcess extends Process
{
    /**
     * @param callable $callback
     *
     * @return int
     */
    public function run($callback = null)
    {
        exec('which unbuffer', $output, $status);
        if (0 === $status) {
            $this->setCommandLine('unbuffer ' . $this->getCommandLine());
        }

        return parent::run($callback);
    }
}
