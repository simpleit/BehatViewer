<?php

namespace BehatViewer\BehatViewerApiBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration,
    JMS\SecurityExtraBundle\Annotation as Security,
    BehatViewer\BehatViewerBundle\Entity,
    BehatViewer\BehatViewerBundle\Controller\BehatViewerController;

class ActionController extends BehatViewerController
{
    /**
     * @param \BehatViewer\BehatViewerBundle\Entity\User    $user
     * @param \BehatViewer\BehatViewerBundle\Entity\Project $project
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Configuration\Route("/analyze/{username}/{project}", name="behatviewer.api.analyze")
     */
    public function analyzeAction(Entity\User $user, Entity\Project $project)
    {
        $msg = array(
			'payload' => $this->getRequest()->get('payload'),
			'project' => $project->getSlug()
		);
        $this->get('old_sound_rabbit_mq.analyze_producer')->publish(serialize($msg));

        return new \Symfony\Component\HttpFoundation\Response();
    }

    /**
     * @param \BehatViewer\BehatViewerBundle\Entity\User    $user
     * @param \BehatViewer\BehatViewerBundle\Entity\Project $project
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Configuration\Route("/build/{username}/{project}", name="behatviewer.api.build")
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
     * @Configuration\Route("/github", name="behatviewer.api.github")
     */
    public function githubAction()
    {
        if (null !== ($payload = $this->getRequest()->get('payload'))) {
            $ghrepository = $payload->repository;

            $repository = $this->getDoctrine()->getManager()->getRepository('BehatViewerBundle:Project');
            $project = $repository->findOneByUsernameAndSlug($this->getUser()->getUsername(), $ghrepository->name);

            if (null !== $project) {
                $msg = array('project' => $project->getSlug());
                $this->get('old_sound_rabbit_mq.build_producer')->publish(serialize($msg));
            }

            return new \Symfony\Component\HttpFoundation\Response();
        } else {
            return $this->createNotFoundException();
        }
    }
}
