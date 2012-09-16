<?php
namespace BehatViewer\BehatViewerWorkerBundle\Builder;

use BehatViewer\BehatViewerBundle\Entity;
use Symfony\Component\Console\Output\OutputInterface;

interface BuilderInterface
{
    public function build(Entity\Strategy $strategy, OutputInterface $output);
}
