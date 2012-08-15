<?php

namespace jubianchi\BehatViewerBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 *
 */
class BehatViewerBundle extends Bundle
{
	public function boot()
	{
		parent::boot();

		$manager = $this->container->get('doctrine')->getManager();
		$platform = $manager->getConnection()->getDatabasePlatform();
		$platform->registerDoctrineTypeMapping('ENUM', 'string');

		\Doctrine\DBAL\Types\Type::addType('status', 'jubianchi\BehatViewerBundle\DBAL\Type\EnumStatusType');
		\Doctrine\DBAL\Types\Type::addType('step_status', 'jubianchi\BehatViewerBundle\DBAL\Type\EnumStepStatusType');
		\Doctrine\DBAL\Types\Type::addType('project_type', 'jubianchi\BehatViewerBundle\DBAL\Type\EnumProjectTypeType');
	}
}
