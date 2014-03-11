<?php

namespace Remdan\ModelManagerBundle\Event;

use Remdan\ModelManagerBundle\Event\AbstractEvent;

class PostRemoveEvent extends AbstractEvent
{
    const EVENT_POST_REMOVE = 'model_manager.event.post_remove';
}