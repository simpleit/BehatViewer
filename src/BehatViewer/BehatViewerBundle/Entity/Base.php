<?php
namespace BehatViewer\BehatViewerBundle\Entity;

use Symfony\Component\Security\Acl\Domain\ObjectIdentity;

abstract class Base
{
    public static function initFromArray(array $values = array())
    {
        $instance = new static();

        foreach ($values as $field => $value) {
            $setter = 'set' . ucfirst($field);
            $instance->$setter($value);
        }

        return $instance;
    }

    public function getIdentity()
    {
        return ObjectIdentity::fromDomainObject($this);
    }
}
