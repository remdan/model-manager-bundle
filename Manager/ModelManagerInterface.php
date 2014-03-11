<?php

namespace Remdan\ModelManagerBundle\Manager;

interface ModelManagerInterface
{
    /**
     * @return string
     */
    public function getEntityClassName();

    /**
     * @return mixed
     */
    public function getEntityManager();

    /**
     * @return mixed
     */
    public function getObjectRepository();

    /**
     * @return mixed
     */
    public function createObject();

    /**
     * @param $object
     * @return mixed
     */
    public function updateObject($object);

    /**
     * @param $object
     * @return mixed
     */
    public function persistObject($object);

    /**
     * @param $object
     * @return mixed
     */
    public function flushObject($object);

    /**
     * @param $object
     * @return mixed
     */
    public function deleteObject($object);

}