<?php

namespace Remdan\ModelManagerBundle\Event;

use Remdan\ModelManagerBundle\Event\AbstractEvent;

class PrePersistEvent extends AbstractEvent
{
    const EVENT_PRE_PERSIST = 'model_manager.event.pre_persist';
}