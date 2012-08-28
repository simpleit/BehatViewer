<?php

namespace BehatViewer\BehatViewerApiBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration,
    JMS\SecurityExtraBundle\Annotation as Security,
    BehatViewer\BehatViewerBundle\Entity,
    BehatViewer\BehatViewerBundle\Controller\BehatViewerController;

class ApiController extends BehatViewerController
{
    /**
     * @return array
     *
     * @Configuration\Route("/api", name="behatviewer.api")
     */
    public function indexAction()
    {
        return new \Symfony\Component\HttpFoundation\Response();
    }

    /**
     * @return array
     *
     * @Configuration\Route("/api/build/{username}/{project}", name="behatviewer.api.build")
     */
    public function buildAction(Entity\User $user, Entity\Project $project)
    {
        $msg = array('project' => $project->getSlug());
        $this->get('old_sound_rabbit_mq.build_producer')->publish(serialize($msg));

        return new \Symfony\Component\HttpFoundation\Response();
    }

    /**
     * @return array
     *
     * @Configuration\Route("/api/github", name="behatviewer.api.github")
     */
    public function githubAction()
    {
        $payload = json_decode($this->getRequest()->get('payload'));
        $ghrepository = $payload->repository;

        $repository = $this->getDoctrine()->getManager()->getRepository('BehatViewerBundle:Project');
        $project = $repository->findOneByUsernameAndSlug($this->getUser()->getUsername(), $ghrepository->name);

        if (null !== $project) {
            $msg = array('project' => $project->getSlug());
            $this->get('old_sound_rabbit_mq.build_producer')->publish(serialize($msg));
        }

        return new \Symfony\Component\HttpFoundation\Response();
    }
}
