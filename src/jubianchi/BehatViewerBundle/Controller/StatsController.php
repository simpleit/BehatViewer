<?php

namespace jubianchi\BehatViewerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template,
    jubianchi\BehatViewerBundle\Form\Type\ProjectType;

class StatsController extends BehatViewerProjectController
{
    /**
     * @return array
     *
     * @Route("/{username}/{project}/stats", name="behatviewer.stats")
     * @Template()
     */
    public function indexAction($username, $project)
    {
        $project = $this->getProject();
        $repository = $this->getDoctrine()->getRepository('BehatViewerBundle:Build');
        $builds = $repository->findLastBuildsForProject($project, 10);

        return $this->getResponse(array(
            'builds' => $builds
        ));
    }
}
