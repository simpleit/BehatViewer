<?php
namespace BehatViewer\BehatViewerWorkerBundle\Builder;

use BehatViewer\BehatViewerBundle\Entity;

class GitLocalBuilder extends GitBuilder
{
    protected function getRepositoryUrl(Entity\Strategy $strategy)
    {
        return $strategy->getRepositoryPath();
    }

    protected function getCloneCommand(Entity\Strategy $strategy)
    {
        return 'git clone --shared ' . $strategy->getPath();
    }

	protected function supports(Entity\Strategy $strategy) {
		return ($strategy instanceof Entity\GitLocalStrategy);
	}
}
