<?php

namespace BehatViewer\BehatViewerBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle,
    Symfony\Component\DependencyInjection\ContainerBuilder,
    Doctrine\DBAL\Types\Type,
    BehatViewer\BehatViewerBundle\DependencyInjection\Compiler\Pass;

/**
 *
 */
class BehatViewerBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $compiler = $container->getCompilerPassConfig();
        $compiler->addPass(new Pass\StrategyCompilerPass());
    }

    public function boot()
    {
        parent::boot();

        $manager = $this->container->get('doctrine')->getManager();
        $platform = $manager->getConnection()->getDatabasePlatform();
        $platform->registerDoctrineTypeMapping('ENUM', 'string');

        if (false === Type::hasType('status')) {
            Type::addType('status', 'BehatViewer\BehatViewerBundle\DBAL\Type\EnumStatusType');
        }

        if (false === Type::hasType('step_status')) {
            Type::addType('step_status', 'BehatViewer\BehatViewerBundle\DBAL\Type\EnumStepStatusType');
        }

        if (false === Type::hasType('project_type')) {
            Type::addType('project_type', 'BehatViewer\BehatViewerBundle\DBAL\Type\EnumProjectTypeType');
        }
    }
}
