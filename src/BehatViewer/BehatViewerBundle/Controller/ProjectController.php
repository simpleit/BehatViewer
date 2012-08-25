<?php
namespace BehatViewer\BehatViewerBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration,
    BehatViewer\BehatViewerBundle\Entity,
    BehatViewer\BehatViewerBundle\Form\Type\ProjectType,
    JMS\SecurityExtraBundle\Annotation as Security,
	Symfony\Component\Security\Acl\Permission\MaskBuilder,
	Symfony\Component\Security\Acl\Exception\AclAlreadyExistsException;

class ProjectController extends BehatViewerProjectController
{
	protected function getStrategies() {
		return $this->container->get('behat_viewer.strategy.provider')->getStrategies();
	}

	protected function getStrategiesForms(Entity\Project $project) {
		$strategies = $this->getStrategies();
		$forms = array();


		foreach($strategies as $strategy) {
			$data = $strategy->getId() === $project->getStrategy()
				? $data = json_decode($project->getConfiguration()->getData())
				: null;

			$forms[$strategy->getLabel()] = $this->get('form.factory')->create($strategy->getForm(), $data)->createView();
		}

		return $forms;
	}

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Configuration\Route("/project/create", name="behatviewer.project.create")
     * @Security\Secure(roles="ROLE_USER")
     * @Configuration\Template()
     */
    public function createAction()
    {
		$request = $this->getRequest();
		$user = $this->getUser();

		$project = new Entity\Project();
		$project->setUser($user);

		$form = $this->get('form.factory')->create(new ProjectType($this->getStrategies()), $project);

		if ('POST' === $request->getMethod()) {
			$success = $this->save($form);

            if ($success) {
                return $this->redirect(
                    $this->generateUrl(
                        'behatviewer.project.edit',
                        array(
                            'username' => $user->getUsername(),
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
			'strategies' => $this->getStrategiesForms($project),
            'hasproject' => (null !== $this->getDoctrine()->getRepository('BehatViewerBundle:Project')->findOneByUser($this->getUser())),
        ));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Configuration\Route("/{username}/{project}", name="behatviewer.project")
     * @Configuration\Route("/{username}/{project}/{type}", requirements={"type" = "list|thumb"}, name="behatviewer.project.switch")
     * @Configuration\Template()
	 * @Security\PreAuthorize("hasPermission(#project, 'VIEW') or #project.getType() == 'public'")
     */
    public function indexAction(Entity\User $user, Entity\Project $project, $type = null)
    {
        return $this->forward(
            'BehatViewerBundle:History:entry',
            array(
                'username' => $user->getUsername(),
                'project' => $project->getSlug(),
                'build' => $project->getLastBuild(),
                'type' => $type
            )
        );
    }

    /**
     * @return array
     *
     * @Configuration\Route("/{username}/{project}/edit", name="behatviewer.project.edit")
	 * @Configuration\Template("BehatViewerBundle:Project:edit.html.twig")
	 * @Security\Secure(roles="ROLE_USER")
	 * @Security\SecureParam(name="project", permissions="EDIT")
     */
    public function editAction(Entity\User $user, Entity\Project $project)
    {
        $request = $this->getRequest();
        $success = false;

        $form = $this->get('form.factory')->create(new ProjectType($this->getStrategies()), $project);

        if ('POST' === $request->getMethod()) {
            $success = $this->save($form);
        }

		$path = sprintf(
			$this->get('kernel')->getRootDir() . '/data/keys/%s-%s.pub',
			$user->getUsername(),
			$project->getSlug()
		);
		$key = '';
		if (file_exists($path)) {
			$key = trim(file_get_contents($path));
		}

        return $this->getResponse(array(
            'form' => $form->createView(),
            'success' => $success || $this->getRequest()->get('success', false),
            'hasproject' => true,
			'ssh_key' => $key,
			'strategies' => $this->getStrategiesForms($project)
        ));
    }

    /**
     * @param \BehatViewer\BehatViewerBundle\Entity\Build|null $build
     *
     * @return \Symfony\Component\HttpFoundation\Response|array
     *
     * @Configuration\Route("/{username}/{project}/delete", name="behatviewer.project.delete")
     * @Security\Secure(roles="ROLE_USER")
	 * @Security\SecureParam(name="project", permissions="DELETE")
     */
    public function deleteAction(Entity\User $user, Entity\Project $project)
    {
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($project);
        $manager->flush();

        return $this->redirect($this->generateUrl('behatviewer.homepage'));
    }

    protected function save(\Symfony\Component\Form\Form $form)
    {
        $form->bind($this->getRequest());
        if ($form->isValid()) {
            $manager = $this->getDoctrine()->getManager();

			$configuration = $form->getData()->getConfiguration();
			if(null === $configuration) {
				$configuration = new Entity\Configuration();
				$form->getData()->setConfiguration($configuration);
			}

			foreach($this->getRequest()->get($form->getData()->getStrategy(), array()) as $param => $value) {
				$configuration->$param = $value;
			}

            $manager->persist($form->getData());
            $manager->flush();

			try {
				$acl = $this->getAclProvider()->createAcl($form->getData()->getIdentity());
			} catch(AclAlreadyExistsException $exception) {
				$acl = $this->getAclProvider()->findAcl($form->getData()->getIdentity());
			}

			$acl->insertObjectAce($this->getUser()->getIdentity(), MaskBuilder::MASK_OWNER);
			$this->getAclProvider()->updateAcl($acl);
        }

        return $form->isValid();
    }
}
