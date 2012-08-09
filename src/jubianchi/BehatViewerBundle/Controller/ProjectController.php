<?php
namespace jubianchi\BehatViewerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Method,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template,
    jubianchi\BehatViewerBundle\Entity,
    jubianchi\BehatViewerBundle\Form\Type\ProjectType,
    JMS\SecurityExtraBundle\Annotation\Secure;

class ProjectController extends BehatViewerProjectController
{
	/**
	 * @return \Symfony\Component\HttpFoundation\Response
	 *
	 * @Route("/project/create", name="behatviewer.project.create")
	 * @Secure(roles="ROLE_USER")
	 * @Template()
	 */
	public function createAction()
	{
		$request = $this->getRequest();

		$project = new Entity\Project();
		$project->setUser($this->getUser());

		$form = $this->get('form.factory')->create(new ProjectType(), $project);

		if ('POST' === $request->getMethod()) {
			$success = $this->save($form);

            if($success) {
                return $this->redirect(
                    $this->generateUrl(
                        'behatviewer.project.edit',
                        array(
                            'username' => $this->getUser()->getUsername(),
                            'project' => $form->getData()->getSlug(),
                            'success' => $success
                        )
                    )
                );
            }
		}

		return $this->getResponse(array(
			'form' => $form->createView(),
			'success' => false,
			'hasproject' => (null !== $this->getDoctrine()->getRepository('BehatViewerBundle:Project')->findOneByUser($this->getUser())),
		));
	}

	/**
	 * @return \Symfony\Component\HttpFoundation\Response
	 *
	 * @Route("/{username}/{project}", name="behatviewer.project")
	 * @Route("/{username}/{project}/{type}", requirements={"type" = "list|thumb"}, name="behatviewer.project.switch")
	 * @Template()
	 */
	public function indexAction($username, $project, $type = 'thumb')
	{
		return $this->forward(
			'BehatViewerBundle:History:entry',
			array(
				'username' => $username,
				'project' => $project,
				'build' => $this->getProject()->getLastBuild(),
				'type' => $type
			)
		);
	}

    /**
     * @return array
     *
     * @Route("/{username}/{project}/edit", name="behatviewer.project.edit")
     * @Secure(roles="ROLE_USER")
     * @Template("BehatViewerBundle:Project:edit.html.twig")
     */
    public function editAction()
    {
        $request = $this->getRequest();
        $success = false;

        $form = $this->get('form.factory')->create(new ProjectType(), $this->getProject());

        if ('POST' === $request->getMethod()) {
            $success = $this->save($form);
        }

        return $this->getResponse(array(
            'form' => $form->createView(),
            'success' => $success || $this->getRequest()->get('success', false),
            'hasproject' => true,
        ));
    }

	/**
	 * @param \jubianchi\BehatViewerBundle\Entity\Build|null $build
	 *
	 * @return \Symfony\Component\HttpFoundation\Response|array
	 *
	 * @Route("/{username}/{project}/delete", name="behatviewer.project.delete")
	 * @Secure(roles="ROLE_USER")
	 */
	public function deleteAction()
	{
		$this->beforeAction();

		$manager = $this->getDoctrine()->getManager();
		$manager->remove($this->getProject());
		$manager->flush();

		return $this->redirect($this->generateUrl('behatviewer.homepage'));
	}

    protected function save(\Symfony\Component\Form\Form $form)
    {
        $form->bind($this->getRequest());

        if ($form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($form->getData());
            $manager->flush();
        }

        return $form->isValid();
    }
}
