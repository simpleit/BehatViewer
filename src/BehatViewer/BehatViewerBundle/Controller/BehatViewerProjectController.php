<?php
namespace BehatViewer\BehatViewerBundle\Controller;

/**
 *
 */
abstract class BehatViewerProjectController extends BehatViewerController
{
    private $project;

    protected function beforeAction()
    {
        parent::beforeAction();

        $repository = $this->getDoctrine()->getRepository('BehatViewerBundle:Project');

        if (null === $this->getUser() || null === $repository->findOneByUser($this->getUser())) {
            throw new \BehatViewer\BehatViewerBundle\Exception\NoProjectConfiguredException();
        }
    }

    /**
     * @return \BehatViewer\BehatViewerBundle\Entity\Project
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    protected function getProject()
    {
        if (null === $this->project) {
            $username = $this->getRequest()->get('username');
            $project = $this->getRequest()->get('project');

            $repository = $this->getDoctrine()->getRepository('BehatViewerBundle:Project');
            $this->project = $repository->findOneByUsernameAndSlug($username, $project);

            if (null === $this->project) {
                throw $this->createNotFoundException();
            }
        }

        return $this->project;
    }

    /**
     * @return array
     */
    public function getResponse(array $variables = array())
    {
        try {
            $project = $this->getProject();
        } catch (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e) {
            $project = null;
        }

        return array_merge(
            array(
                'project' => $project
            ),
            parent::getResponse($variables)
        );
    }
}
