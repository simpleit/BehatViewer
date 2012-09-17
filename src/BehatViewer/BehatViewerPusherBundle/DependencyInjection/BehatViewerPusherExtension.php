<?php
namespace BehatViewer\BehatViewerPusherBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder,
    Symfony\Component\Config\FileLocator,
    Symfony\Component\HttpKernel\DependencyInjection\Extension,
    Symfony\Component\DependencyInjection\Loader;

/**
 *
 */
class BehatViewerPusherExtension extends Extension
{
    /**
     * @param array                                                   $configs
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('behat_viewer.pusher.host', $config['host']);
        $container->setParameter('behat_viewer.pusher.port', $config['port']);
        $container->setParameter('behat_viewer.pusher.key', $config['key']);
        $container->setParameter('behat_viewer.pusher.channel', $config['channel']);
        $container->setParameter('behat_viewer.pusher.secret', $config['secret']);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');
    }
}
