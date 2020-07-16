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
     * @FormAnnotation\FormElement(type="Select", required=true, name="Prodavac")
     */
    protected $code;

    /**
     * @var Client
     *
     * @ORM\ManyToOne(targetEntity="Vemid\ProjectOne\Entity\Entity\Client")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="client_id", referencedColumnName="id")
     * })
     * @FormAnnotation\FormElement(type="Hidden", required=false)
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
     * @ORM\ManyToOne(targetEntity="Client")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="guarantor_id", referencedColumnName="id")
     * })
     * @FormAnnotation\FormElement(type="Text", required=false, name="Garantor")
     */
    protected $guarantor;

    /**
     * @var string|null
     *
     * @ORM\Column(name="note", type="text", length=65535, nullable=true)
     * @FormAnnotation\FormElement(type="TextArea", required=false)
     */
    protected $note;

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
     * @ORM\OneToOne(targetEntity="Registration", mappedBy="purchase")
     */
    private $registration;

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

    /**
     * @return Registration|null
     */
    public function getRegistration(): ?Registration
    {
        return $this->registration;
    }
}
