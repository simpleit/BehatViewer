<?php
namespace BehatViewer\BehatViewerBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration,
    BehatViewer\BehatViewerBundle\Entity,
    BehatViewer\BehatViewerBundle\DBAL\Type\EnumProjectTypeType;

/**
 *
 */
class DefaultController extends BehatViewerController
{
    /**
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @throws \BehatViewer\BehatViewerBundle\Exception\NoProjectConfiguredException
     *
     * @Configuration\Route("/", name="behatviewer.homepage", options={"expose"=true})
     * @Configuration\Template()
     */
    public function indexAction()
    {
        $repository = $this->getDoctrine()->getRepository('BehatViewerBundle:Project');

		if(null !== ($user = $this->getUser())) {
			$projects = $repository->findByUser($this->getUser());
		} else {
			$projects = $repository->findByType(EnumProjectTypeType::TYPE_PUBLIC);
		}

        if (0 === count($projects)) {
            throw new \BehatViewer\BehatViewerBundle\Exception\NoProjectConfiguredException();
        }

        return $this->getResponse(array(
            'projects' => $projects,
			'user' => $this->getUser()
        ));
    }

	/**
	 * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
	 *
	 * @throws \BehatViewer\BehatViewerBundle\Exception\NoProjectConfiguredException
	 *
	 * @Configuration\Route("/{username}", name="behatviewer.userproject")
	 * @Configuration\Template("BehatViewerBundle:Default:index.html.twig")
	 */
	public function userAction(Entity\User $user)
	{
		$repository = $this->getDoctrine()->getRepository('BehatViewerBundle:Project');
		if($user === $this->getUser()) {
			return $this->forward('BehatViewerBundle:Default:index');
		}

		$projects = $repository->findByUserAndType($user, EnumProjectTypeType::TYPE_PUBLIC);

		return $this->getResponse(array(
			'projects' => $projects
		));
	}

    /**
     * @param \BehatViewer\BehatViewerBundle\Entity\Project $project
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Configuration\Route("/{username}/{project}/analyze", name="behatviewer.analyze")
     * @Configuration\Method({"PUT"})
     */
    public function analyzeAction(Entity\Project $project)
    {
        $data = json_decode($this->getRequest()->getContent(), true);

        $analyzer = $this->get('behat_viewer.analyzer');
        $analyzer->analyze($project, $data);

        return  new \Symfony\Component\HttpFoundation\Response();
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Configuration\Route("/thumb", name="behatviewer.homepage.thumb")
     * @Configuration\Template("BehatViewerBundle:Default:index.html.twig")
     */
    public function indexthumbAction()
    {
        return $this->indexAction();
    }
}
