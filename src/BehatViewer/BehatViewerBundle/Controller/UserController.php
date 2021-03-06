<?php

namespace BehatViewer\BehatViewerBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration,
    Symfony\Component\Security\Core\SecurityContext,
    JMS\SecurityExtraBundle\Annotation as Security,
    BehatViewer\BehatViewerBundle\Form\Type\ProfileType,
    BehatViewer\BehatViewerBundle\Form\Type\RegisterType,
    BehatViewer\BehatViewerBundle\Form\Type\PasswordType,
	BehatViewer\BehatViewerCoreBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class UserController extends BehatViewerController
{
    /**
     * @Configuration\Template()
     */
    public function indexAction()
    {
        if (true === $this->get('security.context')->isGranted('ROLE_USER')) {
            return $this->redirect($this->generateUrl('behatviewer.profile'));
        }

        $request = $this->getRequest();
        $session = $request->getSession();

        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }

        return $this->getResponse(array(
            'last_username' => $session->get(SecurityContext::LAST_USERNAME),
            'error'         => $error,
        ));
    }

    /**
     * @Configuration\Template()
     */
    public function checkAction()
    {
        return array();
    }

	/**
	 * @Configuration\Template()
	 */
	public function registerAction()
	{
		$form = $this->get('form.factory')->create(new RegisterType(), new User());
		$success = false;
		$request = $this->getRequest();

		if ('POST' === $request->getMethod()) {
			$form->bindRequest($request);

			if($form->isValid()) {
				$user = $form->getData();
				$user->setToken(md5(uniqid()));

				$repository = $this->getDoctrine()->getRepository('BehatViewerCoreBundle:Role');
				$role = $repository->findOneByRole('ROLE_USER');
				$user->getRolesCollection()->add($role);

				if (($password = $user->getPassword())) {
					if ($password === $form->get('confirm')->getData()) {
						$factory = $this->get('security.encoder_factory');
						$encoder = $factory->getEncoder($user);

						$user->setPassword(
							$encoder->encodePassword(
								$form->getData()->getPassword(),
								$user->getSalt()
							)
						);
					}
				}

				$this->getDoctrine()->getManager()->persist($user);
				$this->getDoctrine()->getManager()->flush();
				$this->getDoctrine()->getManager()->refresh($user);

				$token = new UsernamePasswordToken($user, $user->getPassword(), 'main', $user->getRoles());
				$context = $this->container->get('security.context');
				$context->setToken($token);

				return $this->redirect($this->generateUrl('behatviewer.homepage'));
			}
		}

		return $this->getResponse(array(
			'success' => false,
			'form' => $form->createView(),
		));
	}

    /**
     * @Configuration\Template()
     * @Security\Secure(roles="ROLE_USER")
     */
    public function profileAction()
    {
        $request = $this->getRequest();
        $success = false;
        $user = $this->getUser();
        $form = $this->get('form.factory')->create(new ProfileType(), $user);

        if ('POST' === $request->getMethod()) {
            $form->bindRequest($request);

            if ($form->isValid()) {
                $user->setUsername($form->getData()->getUsername());
                $user->setEmail($form->getData()->getEmail());

                if (($password = $form->getData()->getPassword())) {
                    if ($password === $request->get('confirm')) {
                        $factory = $this->get('security.encoder_factory');
                        $encoder = $factory->getEncoder($user);

                        $user->setPassword(
                            $encoder->encodePassword(
                                $form->getData()->getPassword(),
                                $user->getSalt()
                            )
                        );
                    }
                }

                $this->getDoctrine()->getManager()->persist($user);
                $this->getDoctrine()->getManager()->flush();
                $this->getDoctrine()->getManager()->refresh($user);

                $success = true;
            }
        }

        return $this->getResponse(array(
            'success' => $success,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Configuration\Template()
     * @Security\Secure(roles="ROLE_USER")
     */
    public function passwordAction()
    {
        $request = $this->getRequest();
        $success = false;
        $user = $this->getUser();
        $form = $this->get('form.factory')->create(new PasswordType(), $user);

        $factory = $this->get('security.encoder_factory');
        $encoder = $factory->getEncoder($user);

        if ('POST' === $request->getMethod()) {
            $oldPassword = $user->getPassword();
            $form->bind($request);

            $givenPassword = $encoder->encodePassword(
                $form->get('oldpassword')->getData(),
                $user->getSalt()
            );

            if ($form->isValid()) {
                if ($givenPassword === $oldPassword) {
                    $user->setPassword(
                        $encoder->encodePassword(
                            $form->getData()->getPassword(),
                            $user->getSalt()
                        )
                    );

                    $this->getDoctrine()->getManager()->persist($user);
                    $this->getDoctrine()->getManager()->flush();
                    $this->getDoctrine()->getManager()->refresh($user);

                    $success = true;
                } else {
                    $form->get('oldpassword')->addError(new \Symfony\Component\Form\FormError('Wrong password'));
                }
            }
        }

        return $this->getResponse(array(
            'success' => $success,
            'form' => $form->createView(),
        ));
    }
}
