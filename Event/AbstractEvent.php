<?php

namespace Remdan\ModelManagerBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class AbstractEvent extends Event
{
    /**
     * @var mixed $object
     */
    protected $object;

    /**
     *
     */
    public function __construct()
    {

    }

    /**
     * @return mixed
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * @param $object
     * @return $this
     */
    public function setObject($object)
    {
        $this->object = $object;

        return $this;
    }
}