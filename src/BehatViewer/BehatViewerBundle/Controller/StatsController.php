<?php

namespace BehatViewer\BehatViewerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template,
    BehatViewer\BehatViewerBundle\Form\Type\ProjectType,
	BehatViewer\BehatViewerBundle\Entity;

class StatsController extends BehatViewerProjectController
{
    /**
     * @return array
     *
     * @Route("/{username}/{project}/stats", name="behatviewer.stats")
     * @Template()
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
