<?php
namespace BehatViewer\BehatViewerWorkerBundle\Builder;

use BehatViewer\BehatViewerBundle\Entity;

interface BuilderInterface
{
    function build(Entity\Strategy $strategy);
}
