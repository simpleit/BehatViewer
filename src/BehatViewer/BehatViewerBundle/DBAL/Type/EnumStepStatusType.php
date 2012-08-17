<?php
namespace BehatViewer\BehatViewerBundle\DBAL\Type;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class EnumStepStatusType extends Type
{
    const ENUM_STEP_STATUS = 'step_status';
    const STATUS_PASSED = 'passed';
    const STATUS_FAILED = 'failed';
    const STATUS_SKIPPED = 'skipped';
    const STATUS_PENDING = 'pending';
    const STATUS_UNDEFINED = 'undefined';

    public function getSqlDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return "ENUM('passed', 'failed', 'skipped', 'pending', 'undefined')";
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return $value;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (false === in_array($value, array(self::STATUS_PASSED, self::STATUS_FAILED, self::STATUS_SKIPPED, self::STATUS_PENDING, self::STATUS_UNDEFINED))) {
            throw new \InvalidArgumentException('Invalid step status : ' . $value);
        }

        return $value;
    }

    public function getName()
    {
        return self::ENUM_STEP_STATUS;
    }
}
