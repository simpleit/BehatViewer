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

    public function getAclProvider()
    {
        return $this->get('security.acl.provider');
    }

    /**
     * @param array $variables
     *
     * @return array
     */
    public function getResponse(array $variables = array())
    {
        return array_merge(
            array(
                'session' => $this->getSession(),
                'user' => $this->getRequest()->get('user')
            ),
            $variables
        );
    }

    /**
     * @param string $type
     *
     * @return string
     */
    protected function setViewType($type)
    {
        $key = $this->getRequest()->get('_route') . '.type';
        $this->getSession()->set($key, $type);

        return $type;
    }

    /**
     * @param string|null $default
     *
     * @return string|null
     */
    protected function getViewType($default = null)
    {
        $key = $this->getRequest()->get('_route') . '.type';

        return $this->getSession()->get($key, $default);
    }
}
