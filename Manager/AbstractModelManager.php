<?php

namespace Remdan\ModelManagerBundle\Manager;

use Doctrine\ORM\EntityManager;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\DBAL\LockMode;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Remdan\ModelManagerBundle\Manager\ModelManagerInterface;
use Remdan\ModelManagerBundle\Event\PostRemoveEvent;
use Remdan\ModelManagerBundle\Event\PreRemoveEvent;
use Remdan\ModelManagerBundle\Event\PrePersistEvent;
use Remdan\ModelManagerBundle\Event\PreUpdateEvent;
use Remdan\ModelManagerBundle\Event\PostPersistEvent;
use Remdan\ModelManagerBundle\Event\PostUpdateEvent;


abstract class AbstractModelManager implements ModelManagerInterface
{
    /**
     * @var String
     */
    protected $entityClassName;

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var EventDispatcher $eventDispatcher
     */
    public $eventDispatcher;

    /**
     * @var EntityRepository
     */
    protected $objectRepository;

    /**
     * @param ObjectManager $entityManager
     * @param $entityClassName
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        ObjectManager $entityManager,
        $entityClassName,
        EventDispatcherInterface $eventDispatcher
    )
    {
        $this->entityManager = $entityManager;
        $this->entityClassName = $entityClassName;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @return string
     */
    public function getEntityClassName()
    {
        return $this->entityClassName;
    }

    /**
     * @param null $entityClassName
     * @return $this
     */
    public function setEntityClassName($entityClassName = null)
    {
        $this->entityClassName = $entityClassName;

        return $this;
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * @param ObjectManager $entityManager
     * @return $this
     */
    public function setEntityManager(ObjectManager $entityManager = null)
    {
        $this->entityManager = $entityManager;

        return $this;
    }

    /**
     * @return EventDispatcher|EventDispatcherInterface
     */
    public function getEventDispatcher()
    {
        return $this->eventDispatcher;
    }

    /**
     * @param EventDispatcherInterface $eventDispatcher
     * @return $this
     */
    public function setEventDispatcher(EventDispatcherInterface $eventDispatcher = null)
    {
        $this->eventDispatcher = $eventDispatcher;

        return $this;
    }

    /**
     * @return EntityRepository|mixed
     */
    public function getObjectRepository()
    {
        if (!$this->objectRepository) {
            return $this->getEntityManager()->getRepository($this->entityClassName);
        }

        return $this->objectRepository;
    }

    /**
     * @param EntityRepository $objectRepository
     * @return $this
     */
    public function setObjectRepository(EntityRepository $objectRepository = null)
    {
        $this->objectRepository = $objectRepository;

        return $this;
    }

    /**
     * @return mixed
     */
    public function createObject()
    {
        $entityClass = $this->getEntityClassName();
        $object = new $entityClass();

        return $object;
    }

    /**
     * @param $object
     * @return mixed|null
     */
    public function deleteObject($object)
    {
        $this->checkObjectClass($object);

        $this->dispatchPreRemoveEvent($object);

        $this->getEntityManager()->remove($object);

        $this->dispatchPostRemoveEvent($object);

        return null;
    }

    /**
     * @param $object
     * @return mixed
     */
    public function persistObject($object)
    {
        $this->checkObjectClass($object);

        $this->dispatchPrePersistEvent($object);

        $this->getEntityManager()->persist($object);

        $this->dispatchPostPeristEvent($object);

        return $object;
    }

    /**
     * @param $object
     * @return mixed
     */
    public function flushObject($object = null)
    {
        if ($object) {
            $this->checkObjectClass($object);
        }

        $this->getEntityManager()->flush($object);

        return $object;
    }

    /**
     * @param $object
     * @return mixed
     */
    public function updateObject($object)
    {
        $this->checkObjectClass($object);

        $this->dispatchPreUpdateEvent($object);

        $this->dispatchPostUpdateEvent($object);
    }

    /**
     *
     */
    public function checkObjectClass($object)
    {
        if (!$object instanceof $this->entityClassName) {
            //TODO: Right error handling
            return new \Exception('Wrong Entity');
        }
    }

    /**
     *
     */
    public function clear()
    {
        return $this->getObjectRepository()->clear();
    }

    /**
     * @param $id
     * @param $lockMode
     * @param null $lockVersion
     * @return null|object
     */
    public function find($id, $lockMode = LockMode::NONE, $lockVersion = null)
    {
        return $this->getObjectRepository()->find($id, $lockMode, $lockVersion);
    }

    /**
     * Finds all entities in the repository.
     *
     * @return array The entities.
     */
    public function findAll()
    {
        return $this->getObjectRepository()->findAll();
    }

    /**
     * @param array $criteria
     * @param array $orderBy
     * @param null $limit
     * @param null $offset
     * @return array
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        return $this->getObjectRepository()->findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * @param array $criteria
     * @param array $orderBy
     * @return null|object
     */
    public function findOneBy(array $criteria, array $orderBy = null)
    {
        return $this->getObjectRepository()->findOneBy($criteria, $orderBy);
    }

    /**
     * @param $object
     */
    protected function dispatchPreRemoveEvent($object)
    {
        $preRemoveEvent = new PreRemoveEvent();
        $preRemoveEvent->setObject($object);
        $this->getEventDispatcher()->dispatch(PreRemoveEvent::EVENT_PRE_REMOVE, $preRemoveEvent);
    }

    /**
     * @param $object
     */
    protected function dispatchPostRemoveEvent($object)
    {
        $postRemoveEvent = new PostRemoveEvent();
        $postRemoveEvent->setObject($object);
        $this->getEventDispatcher()->dispatch(PostRemoveEvent::EVENT_POST_REMOVE, $postRemoveEvent);
    }

    /**
     * @param $object
     */
    protected function dispatchPrePersistEvent($object)
    {
        $preRemoveEvent = new PrePersistEvent();
        $preRemoveEvent->setObject($object);
        $this->getEventDispatcher()->dispatch(PrePersistEvent::EVENT_PRE_PERSIST, $preRemoveEvent);
    }

    /**
     * @param $object
     */
    protected function dispatchPreUpdateEvent($object)
    {
        $preRemoveEvent = new PreUpdateEvent;
        $preRemoveEvent->setObject($object);
        $this->getEventDispatcher()->dispatch(PreUpdateEvent::EVENT_PRE_UPDATE, $preRemoveEvent);
    }

    /**
     * @param $object
     */
    protected function dispatchPostPeristEvent($object)
    {
        $preRemoveEvent = new PostPersistEvent();
        $preRemoveEvent->setObject($object);
        $this->getEventDispatcher()->dispatch(PostPersistEvent::EVENT_POST_PERSIST, $preRemoveEvent);
    }

    /**
     * @param $object
     */
    protected function dispatchPostUpdateEvent($object)
    {
        $preRemoveEvent = new PostUpdateEvent();
        $preRemoveEvent->setObject($object);
        $this->getEventDispatcher()->dispatch(PostUpdateEvent::EVENT_POST_UPDATE, $preRemoveEvent);
    }
}
