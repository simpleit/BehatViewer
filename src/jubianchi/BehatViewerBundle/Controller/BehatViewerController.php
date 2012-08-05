<?php

namespace jubianchi\BehatViewerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 *
 */
abstract class BehatViewerController extends Controller
{
    /**
     * @return \jubianchi\BehatViewerBundle\Session
     */
    public function getSession()
    {
        return $this->get('behat_viewer.session');
    }

    protected function beforeAction()
    {
    }

    /**
     * @return array
     */
    public function getResponse(array $variables = array())
    {
        return array_merge(
            array(
                'session' => $this->getSession(),
                'user' => $this->getUser()
            ),
            $variables
        );
    }


}
