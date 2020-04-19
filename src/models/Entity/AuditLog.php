<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Entity\Entity;

use Doctrine\ORM\Mapping as ORM;
use Vemid\ProjectOne\Entity\Entity;

/**
 * AuditLog
 *
 * @ORM\Table(name="audit_logs", indexes={@ORM\Index(name="user_id", columns={"user_id"}), @ORM\Index(name="timestamp", columns={"timestamp", "modified_entity_name", "modified_entity_id", "operation"})})
 * @ORM\Entity
 */
class AuditLog extends Entity
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="modified_entity_name", type="string", length=255, nullable=false)
     */
    private $modifiedEntityName;

    /**
     * @var int
     *
     * @ORM\Column(name="modified_entity_id", type="integer", nullable=false, options={"unsigned"=true})
     */
    private $modifiedEntityId;

    /**
     * @var string
     *
     * @ORM\Column(name="operation", type="string", length=0, nullable=false, options={"default"="CREATE"})
     */
    private $operation = 'CREATE';

    /**
     * @var string|null
     *
     * @ORM\Column(name="old_data", type="blob", length=0, nullable=true)
     */
    private $oldData;

    /**
     * @var string|null
     *
     * @ORM\Column(name="new_data", type="blob", length=0, nullable=true)
     */
    private $newData;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="timestamp", type="datetime", nullable=false)
     */
    private $timestamp;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;


    /**
     * Get id.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set modifiedEntityName.
     *
     * @param string $modifiedEntityName
     *
     * @return AuditLog
     */
    public function setModifiedEntityName($modifiedEntityName): AuditLog
    {
        $this->modifiedEntityName = $modifiedEntityName;

        return $this;
    }

    /**
     * Get modifiedEntityName.
     *
     * @return string
     */
    public function getModifiedEntityName(): string
    {
        return $this->modifiedEntityName;
    }

    /**
     * Set modifiedEntityId.
     *
     * @param int $modifiedEntityId
     *
     * @return AuditLog
     */
    public function setModifiedEntityId($modifiedEntityId): AuditLog
    {
        $this->modifiedEntityId = $modifiedEntityId;

        return $this;
    }

    /**
     * Get modifiedEntityId.
     *
     * @return int
     */
    public function getModifiedEntityId(): int
    {
        return $this->modifiedEntityId;
    }

    /**
     * Set operation.
     *
     * @param string $operation
     *
     * @return AuditLog
     */
    public function setOperation($operation): AuditLog
    {
        $this->operation = $operation;

        return $this;
    }

    /**
     * Get operation.
     *
     * @return string
     */
    public function getOperation(): string
    {
        return $this->operation;
    }

    /**
     * Set oldData.
     *
     * @param string|null $oldData
     *
     * @return AuditLog
     */
    public function setOldData($oldData = null): AuditLog
    {
        $this->oldData = $oldData;

        return $this;
    }

    /**
     * Get oldData.
     *
     * @return string|null
     */
    public function getOldData(): ?string
    {
        return $this->oldData;
    }

    /**
     * Set newData.
     *
     * @param string|null $newData
     *
     * @return AuditLog
     */
    public function setNewData($newData = null): AuditLog
    {
        $this->newData = $newData;

        return $this;
    }

    /**
     * Get newData.
     *
     * @return string|null
     */
    public function getNewData(): ?string
    {
        return $this->newData;
    }

    /**
     * Set timestamp.
     *
     * @param \DateTime $timestamp
     *
     * @return AuditLog
     */
    public function setTimestamp($timestamp): AuditLog
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    /**
     * Get timestamp.
     *
     * @return \DateTime
     */
    public function getTimestamp(): \DateTime
    {
        return $this->timestamp;
    }

    /**
     * Set user.
     *
     * @param User|null $user
     *
     * @return AuditLog
     */
    public function setUser(User $user = null): AuditLog
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user.
     *
     * @return User|null
     */
    public function getUser(): User
    {
        return $this->user;
    }
}
