<?php

namespace BehatViewer\BehatViewerPusherBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder,
    Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 *
 */
class Configuration implements ConfigurationInterface
{
    /**
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

		$treeBuilder->root('behat_viewer_pusher', 'array')
			->addDefaultsIfNotSet()
			->children()
				->scalarNode('host')->cannotBeEmpty()->end()
				->scalarNode('port')->cannotBeEmpty()->end()
				->scalarNode('key')->cannotBeEmpty()->end()
				->scalarNode('secret')->cannotBeEmpty()->end()
				->scalarNode('channel')->cannotBeEmpty()->end()
			->end()
		->end();

        return $treeBuilder;
    }
}
