<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Entity\Entity;

use Doctrine\ORM\Mapping as ORM;
use Vemid\ProjectOne\Common\Annotation as FormAnnotation;
use Vemid\ProjectOne\Entity\Entity;

/**
 * Purchases
 *
 * @ORM\Table(name="purchases", indexes={@ORM\Index(name="client_id", columns={"client_id"}), @ORM\Index(name="code_id", columns={"code_id"}), @ORM\Index(name="guarantor_id", columns={"guarantor_id"}), @ORM\Index(name="payment_type_id", columns={"payment_type_id"})})
 * @ORM\Entity(repositoryClass="Vemid\ProjectOne\Entity\Repository\PurchaseRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Purchase extends Entity
{
    /**
     * @var Code
     *
     * @ORM\ManyToOne(targetEntity="Vemid\ProjectOne\Entity\Entity\Code")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="code_id", referencedColumnName="id")
     * })
     * @FormAnnotation\FormElement(type="Select", required=true, name="Vrsta kupovine")
     */
    protected $code;

    /**
     * @var Client
     *
     * @ORM\ManyToOne(targetEntity="Vemid\ProjectOne\Entity\Entity\Client")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="client_id", referencedColumnName="id")
     * })
     * @FormAnnotation\FormElement(type="Hidden", required=true)
     */
    protected $client;

    /**
     * @var Code
     *
     * @ORM\ManyToOne(targetEntity="Vemid\ProjectOne\Entity\Entity\Code")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="payment_type_id", referencedColumnName="id")
     * })
     * @FormAnnotation\FormElement(type="Select", required=true, name="Način plaćanja")
     */
    protected $paymentType;

    /**
     * @var Client
     *
     * @ORM\ManyToOne(targetEntity="Vemid\ProjectOne\Entity\Entity\Client")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="guarantor_id", referencedColumnName="id")
     * })
     * @FormAnnotation\FormElement(type="Text", required=false, name="Garantor")
     */
    protected $guarantor;

    /**
     * @var string
     *
     * @ORM\Column(name="plates", type="string", length=255, nullable=true)
     * @FormAnnotation\FormElement(type="Text", required=false, hidden=true)
     */
    protected $plates;

    /**
     * @var string
     *
     * @ORM\Column(name="chassis", type="string", length=255, nullable=true)
     * @FormAnnotation\FormElement(type="Text", required=false, hidden=true)
     */
    protected $chassis;

    /**
     * @var string
     *
     * @ORM\Column(name="insurance_level", type="string", length=255, nullable=true)
     * @FormAnnotation\FormElement(type="Text", required=false, hidden=true)
     */
    protected $insuranceLevel;

    /**
     * @var string
     *
     * @ORM\Column(name="model", type="string", length=255, nullable=true)
     * @FormAnnotation\FormElement(type="Text", required=false, hidden=true)
     */
    protected $model;

    /**
     * @var string|null
     *
     * @ORM\Column(name="note", type="text", length=65535, nullable=true)
     * @FormAnnotation\FormElement(type="TextArea", required=false)
     */
    protected $note;

    /**
     * @var string|null
     *
     * @ORM\Column(name="note_2", type="text", length=65535, nullable=true)
     * @FormAnnotation\FormElement(type="TextArea", required=false)
     */
    protected $note2;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="registered_until", type="datetime", nullable=true)
     * @FormAnnotation\FormElement(type="Date", required=false)
     */
    protected $registeredUntil;

    /**
     * @var bool
     *
     * @ORM\Column(name="authorization", type="boolean", nullable=true)
     * @FormAnnotation\FormElement(type="Checkbox", required=false)
     */
    protected $authorization;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    protected $createdAt;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId():  ?int
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
    public function getPlates(): ?string
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
    public function getChassis(): ?string
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
    public function getInsuranceLevel(): ?string
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
    public function getModel(): ?string
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
    public function getAuthorization(): ?bool
    {
        return $this->authorization;
    }

    /**
     * @return Purchase
     * @ORM\PrePersist
     * @throws \Exception
     */
    public function setCreatedAt(): Purchase
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
    public function getPaymentType(): ?Code
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
    public function getCode(): ?Code
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
    public function getClient(): ?Client
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
    public function getGuarantor(): ?Client
    {
        return $this->guarantor;
    }
}
