<?php

namespace BehatViewer\BehatViewerAdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration,
    JMS\SecurityExtraBundle\Annotation as Security,
    BehatViewer\BehatViewerCoreBundle\Entity,
    BehatViewer\BehatViewerAdminBundle\Form\Type\CreateUserType,
    BehatViewer\BehatViewerAdminBundle\Form\Type\EditUserType,
    BehatViewer\BehatViewerBundle\Controller\BehatViewerController;

class AdminController extends BehatViewerController
{
    /**
     * @return array
     *
     * @Configuration\Route("/users/create", name="behatviewer.admin.user.create")
     * @Configuration\Template()
     * @Security\Secure(roles="ROLE_ADMIN")
     */
    public function userCreateAction()
    {
        $request = $this->getRequest();
        $user = new Entity\User();
        $form = $this->get('form.factory')->create(new CreateUserType(), $user);

        if ('POST' === $request->getMethod()) {
            if ($this->save($form, $user)) {
                return  $this->redirect($this->generateUrl('behatviewer.admin.user.edit', array('username' => $user->getUsername())));
            }
        }

        return  $this->getResponse(array(
            'success' => false,
            'user' => $user,
            'form' => $form->createView()
        ));
    }

    /**
     * @return array
     *
     * @Configuration\Route("/users", name="behatviewer.admin.user")
     * @Configuration\Template()
     * @Security\Secure(roles="ROLE_ADMIN")
     */
    public function usersAction()
    {
        $repository = $this->getDoctrine()->getRepository('BehatViewerCoreBundle:User');
        $users = $repository->findAllOrderByLimit(array('username' => 'ASC'));

        return $this->getResponse(array(
            'items' => $users
        ));
    }

    /**
     * @param \BehatViewer\BehatViewerCoreBundle\Entity\User $user
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Configuration\Route("/users/disable/{username}", name="behatviewer.admin.user.disable")
     * @Security\Secure(roles="ROLE_ADMIN")
     */
    public function userDisableAction(Entity\User $user)
    {
        $user->setIsActive(false);

        $this->getDoctrine()->getManager()->persist($user);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirect($this->getRequest()->headers->get('referer'));
    }

    /**
     * @param \BehatViewer\BehatViewerCoreBundle\Entity\User $user
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Configuration\Route("/users/enable/{username}", name="behatviewer.admin.user.enable")
     * @Security\Secure(roles="ROLE_ADMIN")
     */
    public function userEnableAction(Entity\User $user)
    {
        $user->setIsActive(true);

        $this->getDoctrine()->getManager()->persist($user);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirect($this->getRequest()->headers->get('referer'));
    }

    /**
     * @param \BehatViewer\BehatViewerCoreBundle\Entity\User $user
     *
     * @return array
     *
     * @Configuration\Route("/users/{username}", name="behatviewer.admin.user.edit", requirements={"id" = "\d+"})
     * @Configuration\Template()
     * @Security\Secure(roles="ROLE_ADMIN")
     */
    public function userEditAction(Entity\User $user)
    {
        $request = $this->getRequest();
        $form = $this->get('form.factory')->create(
            new EditUserType(false, $user->getId() !== $this->getUser()->getId()),
            $user
        );
        $success = false;

        if ('POST' === $request->getMethod()) {
            $success = $this->save($form, $user);
        }

        return  $this->getResponse(array(
            'success' => $success,
            'user' => $user,
            'form' => $form->createView()
        ));
    }

    /**
     * @param \Symfony\Component\Form\Form                   $form
     * @param \BehatViewer\BehatViewerCoreBundle\Entity\User $user
     *
     * @return bool
     */
    protected function save(\Symfony\Component\Form\Form $form, Entity\User $user)
    {
        $form->bind($this->getRequest());

        if ($form->isValid()) {
            $user->setUsername($form->getData()->getUsername());
            $user->setEmail($form->getData()->getEmail());
            $user->setIsActive($form->getData()->isEnabled());

            if (($password = $form->get('password')->getData()) !== null) {
                if ($password === $form->get('confirm')->getData()) {
                    $factory = $this->get('security.encoder_factory');
                    $encoder = $factory->getEncoder($user);

                    $user->setPassword(
                        $encoder->encodePassword(
                            $password,
                            $user->getSalt()
                        )
                    );

                    if ($user->getToken() == '') {
                        $user->setToken(md5(uniqid()));
                    }
                }
            }

            $this->getDoctrine()->getManager()->persist($user);
            $this->getDoctrine()->getManager()->flush();
        }

        return $form->isValid();
    }
}
