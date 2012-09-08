<?php
namespace BehatViewer\BehatViewerBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration,
    JMS\SecurityExtraBundle\Annotation as Security,
    BehatViewer\BehatViewerBundle\Entity,
    BehatViewer\BehatViewerBundle\Form\Type\ProjectType,
    BehatViewer\BehatViewerBundle\Form\Type\ProjectScriptType,
    BehatViewer\BehatViewerBundle\Form\Type\ProjectRepositoryType,
    Symfony\Component\Security\Acl\Permission\MaskBuilder,
    Symfony\Component\Security\Acl\Exception\AclAlreadyExistsException;

class ProjectController extends BehatViewerProjectController
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Configuration\Route("/project/create", name="behatviewer.project.create")
     * @Configuration\Template()
     * @Security\Secure(roles="ROLE_USER")
     */
    public function createAction()
    {
        $request = $this->getRequest();
        $user = $this->getUser();

        $project = new Entity\Project();
        $project->setUser($user);

        $form = $this->get('form.factory')->create(new ProjectType(ProjectType::HIDE_SCRIPT), $project);

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
            'hasproject' => (null !== $this->getDoctrine()->getRepository('BehatViewerBundle:Project')->findOneByUser($this->getUser())),
        ));
    }

    /**
     * @param \BehatViewer\BehatViewerBundle\Entity\User    $user
     * @param \BehatViewer\BehatViewerBundle\Entity\Project $project
     * @param string|null                                   $type
     *
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
     * @param \BehatViewer\BehatViewerBundle\Entity\User    $user
     * @param \BehatViewer\BehatViewerBundle\Entity\Project $project
     *
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

        $form = $this->get('form.factory')->create(new ProjectType(), $project);

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
            'ssh_key' => $key
        ));
    }

    /**
     * @param \BehatViewer\BehatViewerBundle\Entity\User    $user
     * @param \BehatViewer\BehatViewerBundle\Entity\Project $project
     *
     * @return array
     *
     * @Configuration\Route("/{username}/{project}/edit/script", name="behatviewer.project.edit.script")
     * @Configuration\Template("BehatViewerBundle:Project:edit.script.html.twig")
     * @Security\Secure(roles="ROLE_PREMIUM")
     * @Security\SecureParam(name="project", permissions="EDIT")
     */
    public function editScriptAction(Entity\User $user, Entity\Project $project)
    {
        if (null === $project->getStrategy()) {
            return $this->redirect(
                $this->generateUrl(
                    'behatviewer.project.edit.repository',
                    array(
                        'username' => $user->getUsername(),
                        'project' => $project->getSlug()
                    )
                )
            );
        }

        $request = $this->getRequest();
        $success = false;

        $form = $this->get('form.factory')->create(new ProjectScriptType(), $project);

        if ('POST' === $request->getMethod()) {
            $success = $this->save($form);
        }

        return $this->getResponse(array(
            'form' => $form->createView(),
            'success' => $success || $this->getRequest()->get('success', false),
            'hasproject' => true
        ));
    }

    /**
     * @param \BehatViewer\BehatViewerBundle\Entity\User    $user
     * @param \BehatViewer\BehatViewerBundle\Entity\Project $project
     *
     * @return array
     *
     * @Configuration\Route("/{username}/{project}/edit/repository", name="behatviewer.project.edit.repository")
     * @Configuration\Template("BehatViewerBundle:Project:edit.html.twig")
     * @Security\Secure(roles="ROLE_PREMIUM")
     * @Security\SecureParam(name="project", permissions="EDIT")
     */
    public function editRepositoryAction(Entity\User $user, Entity\Project $project)
    {
        $request = $this->getRequest();
        $success = false;
        $strategy = $project->getStrategy();

        if (null !== $strategy) {
            $form = $this->get('form.factory')->create($strategy->getFormType(), $strategy);
        } else {
            $form = $this->get('form.factory')->create(new ProjectRepositoryType());
        }

        if ('POST' === $request->getMethod()) {
            $form->bind($request);

            $manager = $this->getDoctrine()->getManager();

            if (null === $strategy || $form instanceof ProjectRepositoryType) {
                $strategies = array(
                    'git' => '\\BehatViewer\\BehatViewerBundle\\Entity\\GitStrategy',
                    'git_local' => '\\BehatViewer\\BehatViewerBundle\\Entity\\GitStrategy',
                    'github' => '\\BehatViewer\\BehatViewerBundle\\Entity\\GitStrategy',
                    'local' => '\\BehatViewer\\BehatViewerBundle\\Entity\\GitStrategy'
                );

                $strategy = $strategies[$form->get('strategy')->getData()];
                $strategy = new $strategy();
                $project->setStrategy($strategy);
                $strategy->setProject($project);

                $form = $this->get('form.factory')->create($strategy->getFormType(), $strategy);
                $manager->persist($strategy);
            } else {
                $manager->persist($form->getData());
            }

            $manager->flush();
            $success = (null !== $strategy);
        }

        return $this->getResponse(array(
            'form' => $form->createView(),
            'success' => $success || $this->getRequest()->get('success', false),
            'hasproject' => true
        ));
    }

    /**
     * @param \BehatViewer\BehatViewerBundle\Entity\User    $user
     * @param \BehatViewer\BehatViewerBundle\Entity\Project $project
     *
     * @return \Symfony\Component\HttpFoundation\Response
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

    /**
     * @param \Symfony\Component\Form\Form $form
     *
     * @return bool
     */
    protected function save(\Symfony\Component\Form\Form $form)
    {
        $form->bind($this->getRequest());
        if ($form->isValid()) {
            $manager = $this->getDoctrine()->getManager();

            $manager->persist($form->getData());
            $manager->flush();

            try {
                $acl = $this->getAclProvider()->createAcl($form->getData()->getIdentity());
            } catch (AclAlreadyExistsException $exception) {
                $acl = $this->getAclProvider()->findAcl($form->getData()->getIdentity());
            }

            $acl->insertObjectAce($this->getUser()->getIdentity(), MaskBuilder::MASK_OWNER);
            $this->getAclProvider()->updateAcl($acl);
        }

        return $form->isValid();
    }
}
