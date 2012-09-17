<?php
namespace BehatViewer\BehatViewerWorkerBundle\Builder;

use BehatViewer\BehatViewerCoreBundle\Entity,
    Symfony\Component\Console\Output\OutputInterface;

interface BuilderInterface
{
    public function build(Entity\Strategy $strategy, OutputInterface $output);
}
