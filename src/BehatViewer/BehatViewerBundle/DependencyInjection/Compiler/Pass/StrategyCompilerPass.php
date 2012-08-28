<?php
namespace BehatViewer\BehatViewerBundle\DependencyInjection\Compiler\Pass;

use
    Symfony\Component\DependencyInjection\ContainerBuilder,
    Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface,
    Symfony\Component\DependencyInjection\Reference
;

class StrategyCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (false === $container->hasDefinition('behat_viewer.strategy.provider')) {
            return;
        }

        $definition = $container->getDefinition('behat_viewer.strategy.provider');

        foreach ($container->findTaggedServiceIds('behat_viewer.strategy') as $id => $attributes) {
            $definition->addMethodCall('addStrategy', array($container->get($id)->getId(), new Reference($id)));
        }
    }
}
