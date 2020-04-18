<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Entity;

/**
 * Class Type
 * @package Vemid\ProjectOne\Entity\Entity
 */
class Type
{
    const AUDIT_LOG = 1;
    const ROLE = 3;
    const USER_ROLE_ASSIGNMENT = 4;
    const USER = 2;

    private static $objectNames = [
        self::AUDIT_LOG => 'AuditLog',
        self::ROLE => 'Role',
        self::USER_ROLE_ASSIGNMENT => 'UserRoleAssignment',
        self::USER => 'User',
    ];

    /**
     * @return array
     */
    public static function getObjectNames(): array
    {
        return self::$objectNames;
    }

    /**
     * Get Model convenience method
     * Returns a model instance
     *
     * @param int $objectTypeId
     * @param int $objectId
     * @return null|EntityInterface
     */
    public static function getEntity($objectTypeId, $objectId): ?EntityInterface
    {
        if (array_key_exists($objectTypeId, self::$objectNames)) {
            $entityName = '\\' . self::$objectNames[$objectTypeId];

            if (class_exists($entityName)) {
                $conditions = array('id = :id:', 'bind' => array('id' => $objectId));

                return \call_user_func([$entityName, 'findFirst'], $conditions);
            }
        }

        return null;
    }

    /**
     * @param int $objectTypeId
     * @return EntityInterface[]|null
     */
    public static function getEntities($objectTypeId): ?array
    {
        if (array_key_exists($objectTypeId, self::$objectNames)) {
            $entityName = '\\' . self::$objectNames[$objectTypeId];
            if (class_exists($entityName)) {
                return \call_user_func(array($entityName, 'find'));
            }
        }

        return null;
    }

    /**
     * @param $entityType
     * @return bool
     */
    public static function hasEntityType($entityType): bool
    {
        if (\is_int($entityType) && array_key_exists($entityType, self::$objectNames)) {
            $entityType = self::getObjectName($entityType);
        }

        if (strtoupper($entityType) === $entityType) {
            $entityType = ucfirst(strtolower($entityType));
        }

        return \in_array($entityType, self::$objectNames, true);
    }

    /**
     * @param int $entityType
     * @return string|null
     */
    public static function getObjectName($entityType): ?string
    {
        if (array_key_exists($entityType, self::$objectNames)) {
            return self::$objectNames[$entityType];
        }

        return null;
    }

    /**
     * @param string $objectName
     * @return int|null
     */
    public static function getEntityType($objectName): ?int
    {
        if (\in_array($objectName, self::$objectNames, true)) {
            return array_search($objectName, self::$objectNames, false);
        }

        return null;
    }

    /**
     * @param $index
     * @return EntityInterface|null
     */
    public static function getEntityByIndex($index): ?EntityInterface
    {
        list($entityTypeId, $entityId) = explode('__', $index);

        return self::getEntity($entityTypeId, $entityId);
    }
}
