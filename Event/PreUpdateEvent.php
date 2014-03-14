<?php

namespace Remdan\ModelManagerBundle\Event;

use Remdan\ModelManagerBundle\Event\AbstractEvent;

class PreUpdateEvent extends AbstractEvent
{
    const EVENT_PRE_UPDATE = 'model_manager.event.pre_update';
}