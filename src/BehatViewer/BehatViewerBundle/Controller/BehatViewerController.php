<?php

namespace BehatViewer\BehatViewerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 *
 */
abstract class BehatViewerController extends Controller
{
    /**
     * @return \BehatViewer\BehatViewerBundle\Session
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

    protected function setViewType($type)
    {
        $key = $this->getRequest()->get('_controller') . '.type';
        $this->getSession()->set($key, $type);

        return $type;
    }

    protected function getViewType($default = null)
    {
        $key = $this->getRequest()->get('_controller') . '.type';

        return $this->getSession()->get($key, $default);
    }
}
