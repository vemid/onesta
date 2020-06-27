<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Entity\Entity;

use Doctrine\ORM\Mapping as ORM;
use Vemid\ProjectOne\Common\Annotation as FormAnnotation;
use Vemid\ProjectOne\Entity\Entity;

/**
 * Suppliers
 *
 * @ORM\Table(name="suppliers")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Vemid\ProjectOne\Entity\Repository\SupplierRepository")
 */
class Supplier extends Entity
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     * @FormAnnotation\FormElement(type="Text", required=true)
     */
    protected $name;

    /**
     * @var string|null
     *
     * @ORM\Column(name="phone_number", type="string", length=255, nullable=true)
     * @FormAnnotation\FormElement(type="Text", required=true)
     */
    protected $phoneNumber;

    /**
     * @var string|null
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     * @FormAnnotation\FormElement(type="Email", required=true)
     */
    protected $email;

    /**
     * @var string|null
     *
     * @ORM\Column(name="address", type="string", length=255, nullable=true)
     * @FormAnnotation\FormElement(type="Text", required=true)
     */
    protected $address;

    /**
     * @var string|null
     *
     * @ORM\Column(name="postal_code", type="string", length=255, nullable=true)
     * @FormAnnotation\FormElement(type="Text", required=true)
     */
    protected $postalCode;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    protected $createdAt;

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
     * Set name.
     *
     * @param string $name
     *
     * @return Supplier
     */
    public function setName($name): Supplier
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Set phoneNumber.
     *
     * @param string|null $phoneNumber
     *
     * @return Supplier
     */
    public function setPhoneNumber($phoneNumber = null): Supplier
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * Get phoneNumber.
     *
     * @return string|null
     */
    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    /**
     * Set email.
     *
     * @param string|null $email
     *
     * @return Supplier
     */
    public function setEmail($email = null): Supplier
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email.
     *
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Set address.
     *
     * @param string|null $address
     *
     * @return Supplier
     */
    public function setAddress($address = null): Supplier
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address.
     *
     * @return string|null
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * Set postalCode.
     *
     * @param string|null $postalCode
     *
     * @return Supplier
     */
    public function setPostalCode($postalCode = null): Supplier
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    /**
     * Get postalCode.
     *
     * @return string|null
     */
    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    /**
     * @ORM\PrePersist
     * @return Supplier
     * @throws \Exception
     */
    public function setCreatedAt(): Supplier
    {
        $this->createdAt = new \DateTime();

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

    public function __toString()
    {
        return $this->name;
    }
}
