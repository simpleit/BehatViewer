<?php

namespace jubianchi\BehatViewerBundle;

use Symfony\Component\DependencyInjection\ContainerAware,
    jubianchi\BehatViewerBundle\Entity;

/**
 *
 */
class Session extends ContainerAware
{
    /**
     * @param string $name
     * @param mixed  $default
     *
     * @return mixed
     */
    public function get($name, $default = null)
    {
        return $this->container->get('session')->get($name, $default);
    }

    /**
     * @param string $name
     * @param mixed  $value
     *
     * @return \jubianchi\BehatViewerBundle\Session
     */
    public function set($name, $value)
    {
        $this->container->get('session')->set($name, $value);

        return $this;
    }
}
