<?php
namespace BehatViewer\BehatViewerReportBundle\Analyzer;

use Symfony\Component\EventDispatcher\EventDispatcher,
    Symfony\Component\EventDispatcher\Event,
    Symfony\Component\DependencyInjection\ContainerAwareInterface,
    Symfony\Component\DependencyInjection\ContainerInterface;

abstract class Analyzer extends EventDispatcher implements ContainerAwareInterface, AnalyzerInterface
{
    protected $container;

    /**
     * @param null|\Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @return \Doctrine\Bundle\DoctrineBundle\Registry
     */
    public function getDoctrine()
    {
        return $this->container->get('doctrine');
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        return $this->getDoctrine()->getManager();
    }

    /**
     * @param string $name
     * @param mixed  $data
     *
     * @return \Symfony\Component\EventDispatcher\Event
     */
    public function getEvent($name, $data = null)
    {
        $event = new Event();
        $event->setName($name);
        $event->data = $data;

        return $event;
    }

    /**
     * @param string $name
     * @param mixed  $data
     */
    public function dispatchEvent($name, $data = null)
    {
        $this->dispatch($name, $this->getEvent($name, $data));
    }
}
