<?php
namespace BehatViewer\BehatViewerWorkerBundle\Builder;

use BehatViewer\BehatViewerCoreBundle\Entity;

class GithubBuilder extends GitBuilder
{
    protected function getRepositoryUrl(Entity\Strategy $strategy)
    {
        return sprintf(
            'git://github.com/%s/%s',
            $strategy->getUsername(),
            $strategy->getRepository()
        );
    }

    protected function supports(Entity\Strategy $strategy)
    {
        return ($strategy instanceof Entity\GithubStrategy);
    }
}
