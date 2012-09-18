<?php
namespace BehatViewer\BehatViewerCoreBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle,
    Doctrine\DBAL\Types\Type;

class BehatViewerCoreBundle extends Bundle
{
    public function boot()
    {
        parent::boot();

        $manager = $this->container->get('doctrine')->getManager();
        $platform = $manager->getConnection()->getDatabasePlatform();
        $platform->registerDoctrineTypeMapping('ENUM', 'string');

        if (false === Type::hasType('status')) {
            Type::addType('status', 'BehatViewer\BehatViewerCoreBundle\DBAL\Type\EnumStatusType');
        }

        if (false === Type::hasType('step_status')) {
            Type::addType('step_status', 'BehatViewer\BehatViewerCoreBundle\DBAL\Type\EnumStepStatusType');
        }

        if (false === Type::hasType('project_type')) {
            Type::addType('project_type', 'BehatViewer\BehatViewerCoreBundle\DBAL\Type\EnumProjectTypeType');
        }

		if (false === Type::hasType('job_status')) {
			Type::addType('job_status', 'BehatViewer\BehatViewerCoreBundle\DBAL\Type\EnumJobStatusType');
		}
    }
}
