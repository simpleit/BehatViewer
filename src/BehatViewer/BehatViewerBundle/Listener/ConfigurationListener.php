<?php
namespace BehatViewer\BehatViewerBundle\Listener;

use Symfony\Component\DependencyInjection\ContainerAware,
    Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent,
    Symfony\Component\HttpFoundation\RedirectResponse,
    BehatViewer\BehatViewerBundle\Exception\NoProjectConfiguredException;

class ConfigurationListener extends ContainerAware
{
    /**
     * @param \Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();

        if ($exception instanceof NoProjectConfiguredException) {
            $router = $this->container->get('router');
            $response = new RedirectResponse($router->generate('behatviewer.project.create'));
            $event->setResponse($response);
        }
    }
}
