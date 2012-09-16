<?php
namespace BehatViewer\BehatViewerWorkerBundle\DBAL\Type;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class EnumJobStatusType extends Type
{
    const ENUM_JOB_STATUS = 'job_status';
    const TYPE_PENDING = 'pending';
    const TYPE_RUNNING = 'running';
    const TYPE_SUCCESS = 'success';
    const TYPE_FAILED = 'failed';

    public function getSqlDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return "ENUM('pending', 'running', 'success', 'failed')";
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return $value;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (!in_array($value, array('', self::TYPE_PENDING, self::TYPE_RUNNING, self::TYPE_SUCCESS, self::TYPE_FAILED))) {
            throw new \InvalidArgumentException('Invalid job status : ' . $value);
        }

        return $value;
    }

    public function getName()
    {
        return self::ENUM_JOB_STATUS;
    }
}
