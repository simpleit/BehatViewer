<?php
namespace BehatViewer\BehatViewerApiBundle\Security;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\SecurityFactoryInterface;

class Factory implements SecurityFactoryInterface
{
	public function create(ContainerBuilder $container, $id, $config, $userProvider, $defaultEntryPoint)
	{
		$providerId = 'security.authentication.provider.'.$id;
		$container
			->setDefinition($providerId, new DefinitionDecorator('behat_viewer.api.security.authentication.provider'))
			->replaceArgument(0, new Reference($userProvider))
		;

		$listenerId = 'security.authentication.listener.'.$id;
		$listener = $container->setDefinition($listenerId, new DefinitionDecorator('behat_viewer.api.security.authentication.listener'));

		return array($providerId, $listenerId, $defaultEntryPoint);
	}

	public function getPosition()
	{
		return 'pre_auth';
	}

	public function getKey()
	{
		return 'api';
	}

	public function addConfiguration(NodeDefinition $node)
	{
	}
}
