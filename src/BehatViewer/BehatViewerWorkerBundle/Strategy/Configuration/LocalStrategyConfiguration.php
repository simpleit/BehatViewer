<?php
namespace BehatViewer\BehatViewerWorkerBundle\Strategy\Configuration;

use
	Symfony\Component\DependencyInjection\ContainerAware,
	Symfony\Component\Config
;

class LocalStrategyConfiguration extends Configuration
{
	protected  function getTreeBuilder()
	{
		$builder = new Config\Definition\Builder\TreeBuilder();
		$root = $builder->root('strategy');

		$root
			->ignoreExtraKeys()
			->children()
				->scalarNode('path')->cannotBeEmpty()->end()
				->scalarNode('base_url')->cannotBeEmpty()->end()
			->end()
		;

		return $builder;
	}

	public function getPath() {
		return $this->config['path'];
	}

	public function setPath($path) {
		$this->config['path'] = $path;

		return $this;
	}

	public function getBaseUrl() {
		return $this->config['base_url'];
	}

	public function setBaseUrl($baseUrl) {
		$this->config['base_url'] = $baseUrl;

		return $this;
	}
}
