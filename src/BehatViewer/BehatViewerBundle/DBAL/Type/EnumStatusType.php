<?php
namespace BehatViewer\BehatViewerBundle\DBAL\Type;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class EnumStatusType extends Type
{
    const ENUM_STATUS = 'status';
    const STATUS_PASSED = 'passed';
    const STATUS_FAILED = 'failed';

    public function getSqlDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return "ENUM('passed', 'failed')";
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return $value;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (false === in_array($value, array(self::STATUS_PASSED, self::STATUS_FAILED))) {
            throw new \InvalidArgumentException('Invalid status : ' . $value);
        }

        return $value;
    }

    public function getName()
    {
        return self::ENUM_STATUS;
    }
}
