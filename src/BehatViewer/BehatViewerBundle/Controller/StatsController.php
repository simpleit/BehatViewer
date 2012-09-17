<?php

namespace BehatViewer\BehatViewerBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration,
    BehatViewer\BehatViewerCoreBundle\Entity,
    JMS\SecurityExtraBundle\Annotation as Security;

class StatsController extends BehatViewerProjectController
{
    /**
     * @param \BehatViewer\BehatViewerCoreBundle\Entity\User    $user
     * @param \BehatViewer\BehatViewerCoreBundle\Entity\Project $project
     *
     * @return array
     *
     * @Configuration\Template()
     * @Security\PreAuthorize("hasPermission(#project, 'VIEW') or #project.getType() == 'public'")
     */
    public function indexAction(Entity\User $user, Entity\Project $project)
    {
        $repository = $this->getDoctrine()->getRepository('BehatViewerCoreBundle:Build');
        $builds = $repository->findLastBuildsForProject($project, 50);

        return $this->getResponse(array(
            'builds' => $builds
        ));
    }
}
