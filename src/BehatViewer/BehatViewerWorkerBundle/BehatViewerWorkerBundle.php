<?php

namespace BehatViewer\BehatViewerWorkerBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Doctrine\DBAL\Types\Type;

class BehatViewerWorkerBundle extends Bundle
{
	public function boot()
	{
		parent::boot();

		$manager = $this->container->get('doctrine')->getManager();
		$platform = $manager->getConnection()->getDatabasePlatform();
		$platform->registerDoctrineTypeMapping('ENUM', 'string');

		if (false === Type::hasType('job_status')) {
			Type::addType('job_status', 'BehatViewer\BehatViewerWorkerBundle\DBAL\Type\EnumJobStatusType');
		}
	}
}
