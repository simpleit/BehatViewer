<?php
namespace BehatViewer\BehatViewerBundle\DBAL\Type;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class EnumProjectTypeType extends Type
{
    const ENUM_PROJECT_TYPE = 'project_type';
    const TYPE_PUBLIC = 'public';
    const TYPE_PRIVATE = 'private';

    public function getSqlDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return "ENUM('public', 'private')";
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return $value;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (!in_array($value, array('', self::TYPE_PUBLIC, self::TYPE_PRIVATE))) {
            throw new \InvalidArgumentException('Invalid type : ' . $value);
        }

        return $value;
    }

    public function getName()
    {
        return self::ENUM_PROJECT_TYPE;
    }
}
