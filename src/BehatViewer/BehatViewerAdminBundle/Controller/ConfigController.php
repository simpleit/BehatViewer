<?php

namespace BehatViewer\BehatViewerAdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration,
    JMS\SecurityExtraBundle\Annotation as Security,
    BehatViewer\BehatViewerBundle\Controller\BehatViewerController;

class ConfigController extends BehatViewerController
{
    /**
     * @return array
     *
     * @Configuration\Route("/config", name="behatviewer.config")
     * @Configuration\Template()
     * @Security\Secure(roles="ROLE_ADMIN")
     */
    public function indexAction()
    {
        return $this->getResponse(array(
            'success' => false
        ));
    }
}
