<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\Entity\Event\Subscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Vemid\ProjectOne\Entity\Entity\AuditLog;
use Vemid\ProjectOne\Entity\Entity\PurchaseItem;
use Vemid\ProjectOne\Entity\Entity\SupplierReceiptItem;
use Vemid\ProjectOne\Entity\Entity\User;

/**
 * Class LogActivitySubscriber
 * @package Vemid\ProjectOne\Common\Entity\Event\Subscriber
 */
class LogActivitySubscriber implements EventSubscriber
{
    public const DELETE = 'DELETE';
    public const CREATE = 'CREATE';
    public const UPDATE = 'UPDATE';

    /**
     * @return array|string[]
     */
    public function getSubscribedEvents(): array
    {
        return [
            Events::postPersist,
            Events::preRemove,
            Events::postUpdate,
        ];
    }

    /**
     * @param LifecycleEventArgs $args
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    public function postPersist(LifecycleEventArgs $args)
    {
        $this->logActivity(self::CREATE, $args);
    }

    /**
     * @param LifecycleEventArgs $args
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    public function preRemove(LifecycleEventArgs $args)
    {
        $this->logActivity(self::DELETE, $args);
    }

    /**
     * @param LifecycleEventArgs $args
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    public function postUpdate(LifecycleEventArgs $args)
    {
        $this->logActivity(self::UPDATE, $args);
    }

    /**
     * @param string $action
     * @param LifecycleEventArgs $args
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    private function logActivity(string $action, LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if (!$entity instanceof AuditLog && !$entity instanceof SupplierReceiptItem && !$entity instanceof PurchaseItem) {
            $entityManager = $args->getEntityManager();
            $uow = $entityManager->getUnitOfWork();

            $uow->computeChangeSets();
            $auditNewValues = $uow->getOriginalEntityData($entity);
            foreach ($auditNewValues as $property => &$value) {
                if ($value instanceof \DateTime) {
                    $value = $value->format('Y-m-d H:i:s');
                }
            }

            $auditOldValues = $auditNewValues;
            foreach ((array)$uow->getEntityChangeSet($entity) as $field => $values) {
                $auditOldValues[$field] = $values[0];
            }

            $user = null;
            if (array_key_exists('user', $_SESSION)) {
                $user = $entityManager->find(User::class, $_SESSION['user']['id']);
            }

            $auditLog = new AuditLog();
            $auditLog->setModifiedEntityName(\get_class($entity));
            $auditLog->setModifiedEntityId($entity->getId());
            $auditLog->setOperation($action);
            $auditLog->setOldData(json_encode($auditOldValues));
            $auditLog->setNewData(json_encode($auditNewValues));
            $auditLog->setTimestamp(new \DateTime());
            $auditLog->setUser($user);

            $entityManager->persist($auditLog);
            $entityManager->flush();
        }
    }
}
