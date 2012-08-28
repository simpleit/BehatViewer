<?php
namespace BehatViewer\BehatViewerWorkerBundle\Strategy\Configuration;

use
	Symfony\Component\DependencyInjection\ContainerAware,
	Symfony\Component\Config
;

class GitStrategyConfiguration extends Configuration
{
	protected function getTreeBuilder()
	{
		$builder = new Config\Definition\Builder\TreeBuilder();
		$root = $builder->root('strategy');

		$root
			->ignoreExtraKeys()
			->children()
				->scalarNode('repository_url')->cannotBeEmpty()->end()
				->scalarNode('branch')->cannotBeEmpty()->end()
			->end()
		;

		return $builder;
	}

	public function getRepositoryUrl() {
		return $this->config['repository_url'];
	}

	public function setRepositoryUrl($repositoryUrl) {
		$this->config['repository_url'] = $repositoryUrl;

		return $this;
	}

	public function getBranch() {
		return $this->config['branch'];
	}

	public function setBranch($branch) {
		$this->config['branch'] = $branch;

		return $this;
	}
}
