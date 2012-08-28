<?php
namespace BehatViewer\BehatViewerWorkerBundle\Strategy\Configuration;

use
	Symfony\Component\DependencyInjection\ContainerAware,
	Symfony\Component\Config
;

class GitLocalStrategyConfiguration extends Configuration
{
	protected function getTreeBuilder()
	{
		$builder = new Config\Definition\Builder\TreeBuilder();
		$root = $builder->root('strategy');

		$root
			->ignoreExtraKeys()
			->children()
				->scalarNode('repository_path')->cannotBeEmpty()->end()
				->scalarNode('branch')->cannotBeEmpty()->end()
			->end()
		;

		return $builder;
	}

	public function getRepositoryPath() {
		return $this->config['repository_path'];
	}

	public function setRepositoryPath($repositoryPath) {
		$this->config['repository_path'] = $repositoryPath;

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
