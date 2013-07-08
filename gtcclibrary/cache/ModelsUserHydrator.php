<?php

namespace Hydrators;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;
use Doctrine\ODM\MongoDB\Hydrator\HydratorInterface;
use Doctrine\ODM\MongoDB\UnitOfWork;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ODM. DO NOT EDIT THIS FILE.
 */
class ModelsUserHydrator implements HydratorInterface
{
    private $dm;
    private $unitOfWork;
    private $class;

    public function __construct(DocumentManager $dm, UnitOfWork $uow, ClassMetadata $class)
    {
        $this->dm = $dm;
        $this->unitOfWork = $uow;
        $this->class = $class;
    }

    public function hydrate($document, $data)
    {
        $hydratedData = array();

        /** @Field(type="id") */
        if (isset($data['_id'])) {
            $value = $data['_id'];
            $return = (string) $value;
            $this->class->reflFields['id']->setValue($document, $return);
            $hydratedData['id'] = $return;
        }

        /** @Field(type="string") */
        if (isset($data['username'])) {
            $value = $data['username'];
            $return = (string) $value;
            $this->class->reflFields['username']->setValue($document, $return);
            $hydratedData['username'] = $return;
        }

        /** @Field(type="string") */
        if (isset($data['pwd'])) {
            $value = $data['pwd'];
            $return = (string) $value;
            $this->class->reflFields['pwd']->setValue($document, $return);
            $hydratedData['pwd'] = $return;
        }

        /** @Field(type="string") */
        if (isset($data['email'])) {
            $value = $data['email'];
            $return = (string) $value;
            $this->class->reflFields['email']->setValue($document, $return);
            $hydratedData['email'] = $return;
        }

        /** @Field(type="int") */
        if (isset($data['userType'])) {
            $value = $data['userType'];
            $return = (int) $value;
            $this->class->reflFields['userType']->setValue($document, $return);
            $hydratedData['userType'] = $return;
        }

        /** @Field(type="int") */
        if (isset($data['registerTime'])) {
            $value = $data['registerTime'];
            $return = (int) $value;
            $this->class->reflFields['registerTime']->setValue($document, $return);
            $hydratedData['registerTime'] = $return;
        }

        /** @Field(type="int") */
        if (isset($data['lastLoginTime'])) {
            $value = $data['lastLoginTime'];
            $return = (int) $value;
            $this->class->reflFields['lastLoginTime']->setValue($document, $return);
            $hydratedData['lastLoginTime'] = $return;
        }
        return $hydratedData;
    }
}