<?php
namespace BehatViewer\BehatViewerWorkerBundle\Strategy\Configuration;

use
    Symfony\Component\DependencyInjection\ContainerAware,
    Symfony\Component\Config
;

class GithubStrategyConfiguration extends Configuration
{
    protected function getTreeBuilder()
    {
        $builder = new Config\Definition\Builder\TreeBuilder();
        $root = $builder->root('strategy');

        $root
            ->ignoreExtraKeys()
            ->children()
                ->scalarNode('username')->cannotBeEmpty()->end()
                ->scalarNode('repository')->cannotBeEmpty()->end()
                ->scalarNode('branch')->cannotBeEmpty()->end()
            ->end()
        ;

        return $builder;
    }

    public function getUsername()
    {
        return $this->config['username'];
    }

    public function setRepositoryPath($username)
    {
        $this->config['username'] = $username;

        return $this;
    }

    public function getRepository()
    {
        return $this->config['repository'];
    }

    public function setRepository($repository)
    {
        $this->config['repository'] = $repository;

        return $this;
    }

    public function getBranch()
    {
        return $this->config['branch'];
    }

    public function setBranch($branch)
    {
        $this->config['branch'] = $branch;

        return $this;
    }
}
