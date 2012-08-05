<?php
namespace jubianchi\BehatViewerBundle\Controller;

/**
 *
 */
abstract class BehatViewerSwitchableController extends BehatViewerController
{
    protected function getKey()
    {
        return $this->getRequest()->get('_controller') . 'view_type';
    }

    /**
     * @param string $type
     *
     * @return \jubianchi\BehatViewerBundle\Controller\BehatViewerSwitchableController
     */
    public function setViewType($type)
    {

        $this->getSession()->set($this->getKey(), $type);

        return $this;
    }

    /**
     * @param mixed|null $default
     *
     * @return mixed
     */
    public function getViewType($default = null)
    {
        return $this->getSession()->get($this->getKey(), $default);
    }
}
