<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Entity\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Purchases
 *
 * @ORM\Table(name="purchases", indexes={@ORM\Index(name="client_id", columns={"client_id"}), @ORM\Index(name="code_id", columns={"code_id"}), @ORM\Index(name="guarantor_id", columns={"guarantor_id"}), @ORM\Index(name="payment_type_id", columns={"payment_type_id"})})
 * @ORM\Entity
 */
class Purchase
{
    /**
     * @var string
     *
     * @ORM\Column(name="plates", type="string", length=255, nullable=false)
     */
    private $plates;

    /**
     * @var string
     *
     * @ORM\Column(name="chassis", type="string", length=255, nullable=false)
     */
    private $chassis;

    /**
     * @var string
     *
     * @ORM\Column(name="insurance_level", type="string", length=255, nullable=false)
     */
    private $insuranceLevel;

    /**
     * @var string
     *
     * @ORM\Column(name="model", type="string", length=255, nullable=false)
     */
    private $model;

    /**
     * @var string|null
     *
     * @ORM\Column(name="note", type="text", length=65535, nullable=true)
     */
    private $note;

    /**
     * @var string|null
     *
     * @ORM\Column(name="note_2", type="text", length=65535, nullable=true)
     */
    private $note2;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="registered_until", type="datetime", nullable=false)
     */
    private $registeredUntil;

    /**
     * @var bool
     *
     * @ORM\Column(name="authorization", type="boolean", nullable=false)
     */
    private $authorization;

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
     * @var Code
     *
     * @ORM\ManyToOne(targetEntity="Vemid\ProjectOne\Entity\Entity\Code")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="payment_type_id", referencedColumnName="id")
     * })
     */
    private $paymentType;

    /**
     * @var Code
     *
     * @ORM\ManyToOne(targetEntity="Vemid\ProjectOne\Entity\Entity\Code")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="code_id", referencedColumnName="id")
     * })
     */
    private $code;

    /**
     * @var Client
     *
     * @ORM\ManyToOne(targetEntity="Vemid\ProjectOne\Entity\Entity\Client")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="client_id", referencedColumnName="id")
     * })
     */
    private $client;

    /**
     * @var Client
     *
     * @ORM\ManyToOne(targetEntity="Vemid\ProjectOne\Entity\Entity\Client")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="guarantor_id", referencedColumnName="id")
     * })
     */
    private $guarantor;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId():  int
    {
        return $this->id;
    }

    /**
     * Set plates.
     *
     * @param string $plates
     *
     * @return Purchase
     */
    public function setPlates($plates): Purchase
    {
        $this->plates = $plates;

        return $this;
    }

    /**
     * Get plates.
     *
     * @return string
     */
    public function getPlates(): string
    {
        return $this->plates;
    }

    /**
     * Set chassis.
     *
     * @param string $chassis
     *
     * @return Purchase
     */
    public function setChassis($chassis): Purchase
    {
        $this->chassis = $chassis;

        return $this;
    }

    /**
     * Get chassis.
     *
     * @return string
     */
    public function getChassis(): string
    {
        return $this->chassis;
    }

    /**
     * Set insuranceLevel.
     *
     * @param string $insuranceLevel
     *
     * @return Purchase
     */
    public function setInsuranceLevel($insuranceLevel): Purchase
    {
        $this->insuranceLevel = $insuranceLevel;

        return $this;
    }

    /**
     * Get insuranceLevel.
     *
     * @return string
     */
    public function getInsuranceLevel(): string
    {
        return $this->insuranceLevel;
    }

    /**
     * Set model.
     *
     * @param string $model
     *
     * @return Purchase
     */
    public function setModel($model): Purchase
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Get model.
     *
     * @return string
     */
    public function getModel(): string
    {
        return $this->model;
    }

    /**
     * Set note.
     *
     * @param string|null $note
     *
     * @return Purchase
     */
    public function setNote($note = null): Purchase
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
     * Set note2.
     *
     * @param string|null $note2
     *
     * @return Purchase
     */
    public function setNote2($note2 = null): Purchase
    {
        $this->note2 = $note2;

        return $this;
    }

    /**
     * Get note2.
     *
     * @return string|null
     */
    public function getNote2(): ?string
    {
        return $this->note2;
    }

    /**
     * Set registeredUntil.
     *
     * @param \DateTime $registeredUntil
     *
     * @return Purchase
     */
    public function setRegisteredUntil($registeredUntil): Purchase
    {
        $this->registeredUntil = $registeredUntil;

        return $this;
    }

    /**
     * Get registeredUntil.
     *
     * @return \DateTime
     */
    public function getRegisteredUntil(): \DateTime
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
    public function setAuthorization($authorization): Purchase
    {
        $this->authorization = $authorization;

        return $this;
    }

    /**
     * Get authorization.
     *
     * @return bool
     */
    public function getAuthorization(): bool
    {
        return $this->authorization;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return Purchase
     */
    public function setCreatedAt($createdAt): Purchase
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt.
     *
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * Set paymentType.
     *
     * @param Code|null $paymentType
     *
     * @return Purchase
     */
    public function setPaymentType(Code $paymentType = null): Purchase
    {
        $this->paymentType = $paymentType;

        return $this;
    }

    /**
     * Get paymentType.
     *
     * @return Code|null
     */
    public function getPaymentType(): Code
    {
        return $this->paymentType;
    }

    /**
     * Set code.
     *
     * @param Code|null $code
     *
     * @return Purchase
     */
    public function setCode(Code $code = null): Purchase
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code.
     *
     * @return Code|null
     */
    public function getCode(): Code
    {
        return $this->code;
    }

    /**
     * Set client.
     *
     * @param Client|null $client
     *
     * @return Purchase
     */
    public function setClient(Client $client = null): Purchase
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get client.
     *
     * @return Client|null
     */
    public function getClient(): Client
    {
        return $this->client;
    }

    /**
     * Set guarantor.
     *
     * @param Client|null $guarantor
     *
     * @return Purchase
     */
    public function setGuarantor(Client $guarantor = null): Purchase
    {
        $this->guarantor = $guarantor;

        return $this;
    }

    /**
     * Get guarantor.
     *
     * @return Client|null
     */
    public function getGuarantor(): Client
    {
        return $this->guarantor;
    }
}
