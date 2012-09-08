<?php
namespace BehatViewer\BehatViewerWorkerBundle\Builder;

use BehatViewer\BehatViewerBundle\Entity;

interface BuilderInterface
{
    public function build(Entity\Strategy $strategy);
}
