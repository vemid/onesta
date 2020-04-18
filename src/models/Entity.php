<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Entity
 * @package Vemid\ProjectOne\Entity\Entity
 * @ORM\MappedSuperclass
 */
class Entity implements EntityInterface
{
    protected $identityField = 'id';

    /**
     * {@inheritdoc}
     */
    public function getEntityName(): ?string
    {
        $dummy = explode('\\', static::class);

        return end($dummy);
    }

    /**
     * {@inheritDoc}
     */
    public function getProperty(string $property)
    {
        $method = 'get' . ucfirst($property);
        if (method_exists($this, $method)) {
            return  $this->$method();
        }

        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function setProperty(string $property, $value): self
    {
        $method = 'set' . ucfirst($property);
        if (method_exists($this, $method)) {
            if ($value === '') {
                $value = null;
            }

            $this->$method($value);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getEntityId(): int
    {
        return (int)$this->getProperty($this->identityField);
    }

    /**
     * @inheritDoc
     */
    public function getEntityTypeId(): int
    {
        return Type::getEntityType(static::class);
    }

    /**
     * @inheritDoc
     */
    public function getEntityIdentifier(): string
    {
        return $this->getEntityTypeId() . '__' . ($this->getEntityId() ?: 0);
    }

    /**
     * @inheritDoc
     */
    public function getIdentityField(): string
    {
        return $this->identityField;
    }

    /**
     * @inheritDoc
     */
    public function getDisplayName(): string
    {
        return method_exists($this , '__toString') ? (string) $this : sprintf('%s %d', static::class, $this->getEntityId());
    }
}
