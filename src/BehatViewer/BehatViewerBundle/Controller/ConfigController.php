<?php

namespace BehatViewer\BehatViewerBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration,
    JMS\SecurityExtraBundle\Annotation as Security,
    BehatViewer\BehatViewerBundle\Form\Type\ProjectType;

/**
 * @Configuration\Route("/config")
 */
class ConfigController extends BehatViewerController
{
    /**
     * @return array
     *
     * @Configuration\Route("/", name="behatviewer.config")
     * @Security\Secure(roles="ROLE_ADMIN")
     * @Configuration\Template()
     */
    public function indexAction()
    {
        return $this->getResponse(array(
            'success' => false
        ));
    }
}
