<?php

namespace Remdan\ModelManagerBundle\Event;

use Remdan\ModelManagerBundle\Event\AbstractEvent;

class PostPersistEvent extends AbstractEvent
{
    const EVENT_POST_PERSIST = 'model_manager.event.post_persist';
}