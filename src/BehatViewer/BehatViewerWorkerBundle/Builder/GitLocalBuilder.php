<?php
namespace BehatViewer\BehatViewerWorkerBundle\Builder;

use BehatViewer\BehatViewerCoreBundle\Entity;

class GitLocalBuilder extends GitBuilder
{
    protected function getRepositoryUrl(Entity\Strategy $strategy)
    {
        return $strategy->getPath();
    }

    protected function getCloneCommand(Entity\Strategy $strategy, $path = null)
    {
        return 'git clone --shared ' . $this->getRepositoryUrl($strategy) . ' ' . $path;
    }

    protected function supports(Entity\Strategy $strategy)
    {
        return ($strategy instanceof Entity\GitLocalStrategy);
    }
}
