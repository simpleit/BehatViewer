<?php

use Doctrine\Common\Annotations\AnnotationRegistry;

require_once __DIR__ . '/../vendor/symfony/symfony/src/Symfony/Component/ClassLoader/UniversalClassLoader.php';
require_once __DIR__ . '/autoload.php';

AnnotationRegistry::registerFile(__DIR__.'/../vendor/symfony/symfony/src/Symfony/Bridge/Doctrine/Validator/Constraints/UniqueEntity.php');
AnnotationRegistry::registerFile(__DIR__.'/../vendor/symfony/symfony/src/Symfony/Component/Validator/Constraints/NotBlank.php');
AnnotationRegistry::registerFile(__DIR__.'/../vendor/symfony/symfony/src/Symfony/Component/Validator/Constraints/Url.php');
AnnotationRegistry::registerFile(__DIR__.'/../vendor/symfony/symfony/src/Symfony/Component/Validator/Constraints/Email.php');

AnnotationRegistry::registerFile(__DIR__.'/../vendor/sensio/framework-extra-bundle/Sensio/Bundle/FrameworkExtraBundle/Configuration/ParamConverter.php');
AnnotationRegistry::registerFile(__DIR__.'/../vendor/sensio/framework-extra-bundle/Sensio/Bundle/FrameworkExtraBundle/Configuration/Template.php');

AnnotationRegistry::registerFile(__DIR__.'/../vendor/jms/security-extra-bundle/JMS/SecurityExtraBundle/Annotation/PreAuthorize.php');