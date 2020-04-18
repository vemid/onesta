<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Entity;

/**
 * Interface EntityInterface
 * @package Vemid\ProjectOne\Entity\Entity
 */
interface EntityInterface
{
    /**
     * Gets Entity Class name
     *
     * @return string
     */
    public function getEntityName(): ?string;


    /**
     * @return int
     */
    public function getEntityId(): int;

    /**
     * @return int
     */
    public function getEntityTypeId(): int;


    /**
     * @return string
     */
    public function getEntityIdentifier(): string;

    /**
     * @return string
     */
    public function getIdentityField(): string;

    /**
     * @return string
     */
    public function getDisplayName(): string;

    /**
     * @param string $property
     * @return mixed
     */
    public function getProperty(string $property);

    /**
     * @param string $property
     * @param string|int|null $value
     * @return Entity
     */
    public function setProperty(string $property, $value): Entity;
}