<?php

namespace BehatViewer\BehatViewerBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration,
    JMS\SecurityExtraBundle\Annotation as Security,
    BehatViewer\BehatViewerBundle\Form\Type\ProjectType,
    BehatViewer\BehatViewerBundle\Entity,
    BehatViewer\BehatViewerBundle\Form\Type\CreateUserType,
    BehatViewer\BehatViewerBundle\Form\Type\EditUserType;

/**
 * @Configuration\Route("/admin")
 */
class AdminController extends BehatViewerController
{
    /**
     * @return array
     *
     * @Configuration\Route("/users/create", name="behatviewer.usercreate")
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
                return  $this->redirect($this->generateUrl('behatviewer.useredit', array('username' => $user->getUsername())));
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
     * @Configuration\Route("/users", name="behatviewer.users")
     * @Configuration\Template()
     * @Security\Secure(roles="ROLE_ADMIN")
     */
    public function usersAction()
    {
        $repository = $this->getDoctrine()->getRepository('BehatViewerBundle:User');
        $users = $repository->findAllOrderByLimit(array('username' => 'ASC'));

        return $this->getResponse(array(
            'items' => $users
        ));
    }

    /**
     * @Configuration\Route("/users/disable/{username}", name="behatviewer.userdisable")
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
     * @Configuration\Route("/users/enable/{username}", name="behatviewer.userenable")
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
     * @return array
     *
     * @Configuration\Route("/users/{username}", name="behatviewer.useredit", requirements={"id" = "\d+"})
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
