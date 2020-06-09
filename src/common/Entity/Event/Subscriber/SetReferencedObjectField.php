<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\Entity\Event\Subscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Vemid\ProjectOne\Entity\EntityInterface;

/**
 * Class SetReferencedObjectField
 * @package Vemid\ProjectOne\Common\Entity\Event\Subscriber
 */
class SetReferencedObjectField implements EventSubscriber
{
    public const CREATE = 'CREATE';
    public const UPDATE = 'UPDATE';

    /**
     * @inheritDoc
     */
    public function getSubscribedEvents()
    {
        return [
            Events::preUpdate,
            Events::prePersist,
        ];
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $this->setReferencedObjectField(self::CREATE, $args);
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function preUpdate(LifecycleEventArgs $args)
    {
        $this->setReferencedObjectField(self::UPDATE, $args);
    }

    private function setReferencedObjectField(string $action, LifecycleEventArgs $args)
    {
        /** @var EntityInterface $entity */
        $entity = $args->getObject();
        $entityManager = $args->getEntityManager();
        $classMetadata = $entityManager->getClassMetadata(get_class($entity));

        $reflect = new \ReflectionClass($entity);
        $vars = $entity->toArray();

        foreach ($classMetadata->getAssociationMappings() as $field) {
            $value = $vars[$field['fieldName']] ?? null;

            if (!$value || is_object($value) || in_array($field['type'], [ClassMetadataInfo::MANY_TO_MANY, ClassMetadataInfo::ONE_TO_MANY], false)) {
                continue;
            }

            $relationClass = sprintf('%s\\%s', $reflect->getNamespaceName(), ucfirst($field['fieldName']));
            if (!$reference = $entityManager->getReference($relationClass, $value)) {
                throw new \DomainException(sprintf('For property %s not found relation', $field['fieldName']));
            }

            $entity->setProperty($field['fieldName'], $reference);
        }
    }
}