<?php

namespace Remdan\ModelManagerBundle\Event;

use Remdan\ModelManagerBundle\Event\AbstractEvent;

class PostUpdateEvent extends AbstractEvent
{
    const EVENT_POST_UPDATE = 'model_manager.event.post_update';
}