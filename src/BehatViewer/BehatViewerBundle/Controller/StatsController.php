<?php

namespace BehatViewer\BehatViewerBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration,
	BehatViewer\BehatViewerBundle\Entity,
	JMS\SecurityExtraBundle\Annotation as Security;

class StatsController extends BehatViewerProjectController
{
    /**
     * @return array
     *
     * @Configuration\Route("/{username}/{project}/stats", name="behatviewer.stats")
     * @Configuration\Template()
	 * @Security\PreAuthorize("hasPermission(#project, 'VIEW') or #project.getType() == 'public'")
     */
    public function indexAction(Entity\User $user, Entity\Project $project)
    {
        $repository = $this->getDoctrine()->getRepository('BehatViewerBundle:Build');
        $builds = $repository->findLastBuildsForProject($project, 50);

        return $this->getResponse(array(
            'builds' => $builds
        ));
    }
}
