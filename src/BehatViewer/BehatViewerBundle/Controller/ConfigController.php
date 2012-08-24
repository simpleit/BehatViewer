<?php

namespace BehatViewer\BehatViewerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template,
    JMS\SecurityExtraBundle\Annotation\Secure,
    BehatViewer\BehatViewerBundle\Form\Type\ProjectType;

/**
 * @Route("/config")
 */
class ConfigController extends BehatViewerController
{
    /**
     * @return array
     *
     * @Route("/", name="behatviewer.config")
     * @Secure(roles="ROLE_ADMIN")
     * @Template()
     */
    public function indexAction()
    {
        return $this->getResponse(array(
            'success' => false
        ));
    }
}
