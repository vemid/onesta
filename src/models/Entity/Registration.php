<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Entity\Entity;

use Doctrine\ORM\Mapping as ORM;
use Vemid\ProjectOne\Common\Annotation as FormAnnotation;

/**
 * Registrations
 *
 * @ORM\Table(name="registrations", indexes={@ORM\Index(name="purchase_id", columns={"purchase_id"})})
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class Registration extends Stock
{
    /**
     * @var string|null
     *
     * @ORM\Column(name="plates", type="string", length=255, nullable=true)
     * @FormAnnotation\FormElement(type="Text", required=false, hidden=true)
     */
    private $plates;

    /**
     * @var string|null
     *
     * @ORM\Column(name="chassis", type="string", length=255, nullable=true)
     * @FormAnnotation\FormElement(type="Text", required=false, hidden=true)
     */
    private $chassis;

    /**
     * @var string|null
     *
     * @ORM\Column(name="insurance_level", type="string", length=255, nullable=true)
     * @FormAnnotation\FormElement(type="Text", required=false, hidden=true)
     */
    private $insuranceLevel;

    /**
     * @var string|null
     *
     * @ORM\Column(name="model", type="string", length=255, nullable=true)
     * @FormAnnotation\FormElement(type="Text", required=false, hidden=true)
     */
    private $model;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="registered_until", type="datetime", nullable=true)
     * @FormAnnotation\FormElement(type="Date", required=false)
     */
    private $registeredUntil;

    /**
     * @var bool
     *
     * @ORM\Column(name="authorization", type="boolean", nullable=true)
     * @FormAnnotation\FormElement(type="Checkbox", required=false, name="Saglasnost registracije u ime kupca")
     */
    protected $authorization;

    /**
     * @var string|null
     *
     * @ORM\Column(name="note", type="text", length=0, nullable=true)
     * @FormAnnotation\FormElement(type="TextArea", required=false)
     */
    private $note;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var Purchase
     *
     * @ORM\ManyToOne(targetEntity="Purchase")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="purchase_id", referencedColumnName="id")
     * })
     */
    private $purchase;


    /**
     * Set plates.
     *
     * @param string|null $plates
     *
     * @return Registration
     */
    public function setPlates($plates = null): Registration
    {
        $this->plates = $plates;

        return $this;
    }

    /**
     * Get plates.
     *
     * @return string|null
     */
    public function getPlates()
    {
        return $this->plates;
    }

    /**
     * Set chassis.
     *
     * @param string|null $chassis
     *
     * @return Registration
     */
    public function setChassis($chassis = null): Registration
    {
        $this->chassis = $chassis;

        return $this;
    }

    /**
     * Get chassis.
     *
     * @return string|null
     */
    public function getChassis(): ?string
    {
        return $this->chassis;
    }

    /**
     * Set insuranceLevel.
     *
     * @param string|null $insuranceLevel
     *
     * @return Registration
     */
    public function setInsuranceLevel($insuranceLevel = null): Registration
    {
        $this->insuranceLevel = $insuranceLevel;

        return $this;
    }

    /**
     * Get insuranceLevel.
     *
     * @return string|null
     */
    public function getInsuranceLevel(): string
    {
        return $this->insuranceLevel;
    }

    /**
     * Set model.
     *
     * @param string|null $model
     *
     * @return Registration
     */
    public function setModel($model = null): Registration
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Get model.
     *
     * @return string|null
     */
    public function getModel(): ?string
    {
        return $this->model;
    }

    /**
     * Set registeredUntil.
     *
     * @param \DateTime|null $registeredUntil
     *
     * @return Registration
     */
    public function setRegisteredUntil($registeredUntil = null): Registration
    {
        $this->registeredUntil = $registeredUntil;

        return $this;
    }

    /**
     * Get registeredUntil.
     *
     * @return \DateTime|null
     */
    public function getRegisteredUntil(): ?\DateTime
    {
        return $this->registeredUntil;
    }

    /**
     * Set authorization.
     *
     * @param bool $authorization
     *
     * @return Purchase
     */
    public function setAuthorization($authorization): Registration
    {
        $this->authorization = $authorization;

        return $this;
    }

    /**
     * Get authorization.
     *
     * @return bool
     */
    public function getAuthorization(): ?bool
    {
        return $this->authorization;
    }

    /**
     * Set note.
     *
     * @param string|null $note
     *
     * @return Registration
     */
    public function setNote($note = null): Registration
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get note.
     *
     * @return string|null
     */
    public function getNote(): ?string
    {
        return $this->note;
    }

    /**
     * @return Registration
     * @ORM\PrePersist
     * @throws \Exception
     */
    public function setCreatedAt(): Registration
    {
        $this->createdAt = new \DateTime();

        return $this;
    }

    /**
     * Get createdAt.
     *
     * @return \DateTime
     */
    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Set purchase.
     *
     * @param Purchase|null $purchase
     *
     * @return Registration
     */
    public function setPurchase(Purchase $purchase = null): Registration
    {
        $this->purchase = $purchase;

        return $this;
    }

    /**
     * Get purchase.
     *
     * @return Purchase|null
     */
    public function getPurchase(): ?Purchase
    {
        return $this->purchase;
    }
}
