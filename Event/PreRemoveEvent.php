<?php

namespace Remdan\ModelManagerBundle\Event;

use Remdan\ModelManagerBundle\Event\AbstractEvent;

class PreRemoveEvent extends AbstractEvent
{
    const EVENT_PRE_REMOVE = 'model_manager.event.pre_remove';
}