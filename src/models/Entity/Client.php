<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Entity\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Vemid\ProjectOne\Entity\Entity;

/**
 * Clients
 *
 * @ORM\Table(name="clients", indexes={@ORM\Index(name="guarantor_id", columns={"guarantor_id"})})
 * @ORM\Entity
 */
class Client extends Entity
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
     * @var string|null
     *
     * @ORM\Column(name="type", type="string", length=0, nullable=true)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=255, nullable=false)
     */
    private $firstName;

    /**
     * @var string|null
     *
     * @ORM\Column(name="last_name", type="string", length=255, nullable=true)
     */
    private $lastName;

    /**
     * @var string|null
     *
     * @ORM\Column(name="phone_number", type="string", length=255, nullable=true)
     */
    private $phoneNumber;

    /**
     * @var string|null
     *
     * @ORM\Column(name="address", type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @var string|null
     *
     * @ORM\Column(name="postal_code", type="string", length=255, nullable=true)
     */
    private $postalCode;

    /**
     * @var string|null
     *
     * @ORM\Column(name="city", type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @var string|null
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="jbmg", type="string", length=255, nullable=false)
     */
    private $jbmg;

    /**
     * @var string|null
     *
     * @ORM\Column(name="pib", type="string", length=255, nullable=true)
     */
    private $pib;

    /**
     * @var string|null
     *
     * @ORM\Column(name="country_code", type="string", length=255, nullable=true)
     */
    private $countryCode;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var Client
     *
     * @ORM\ManyToOne(targetEntity="Client")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="guarantor_id", referencedColumnName="id")
     * })
     */
    private $guarantor;

    /**
     * @ORM\OneToMany(targetEntity="Client", mappedBy="guarantor")
     */
    private $clients;

    /**
     * Client constructor.
     */
    public function __construct()
    {
        $this->clients = new ArrayCollection();
    }

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
     * Set type.
     *
     * @param string|null $type
     *
     * @return Client
     */
    public function setType($type = null): Client
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     *
     * @return string|null
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Set firstName.
     *
     * @param string $firstName
     *
     * @return Client
     */
    public function setFirstName($firstName): Client
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName.
     *
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * Set lastName.
     *
     * @param string|null $lastName
     *
     * @return Client
     */
    public function setLastName($lastName = null): Client
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName.
     *
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * Set phoneNumber.
     *
     * @param string|null $phoneNumber
     *
     * @return Client
     */
    public function setPhoneNumber($phoneNumber = null): Client
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
     * Set address.
     *
     * @param string|null $address
     *
     * @return Client
     */
    public function setAddress($address = null): Client
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
     * @return Client
     */
    public function setPostalCode($postalCode = null): Client
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
     * Set city.
     *
     * @param string|null $city
     *
     * @return Client
     */
    public function setCity($city = null): Client
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city.
     *
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * Set email.
     *
     * @param string|null $email
     *
     * @return Client
     */
    public function setEmail($email = null): Client
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
     * Set jbmg.
     *
     * @param string $jbmg
     *
     * @return Client
     */
    public function setJbmg($jbmg): Client
    {
        $this->jbmg = $jbmg;

        return $this;
    }

    /**
     * Get jbmg.
     *
     * @return string
     */
    public function getJbmg(): string
    {
        return $this->jbmg;
    }

    /**
     * Set pib.
     *
     * @param string|null $pib
     *
     * @return Client
     */
    public function setPib($pib = null): Client
    {
        $this->pib = $pib;

        return $this;
    }

    /**
     * Get pib.
     *
     * @return string|null
     */
    public function getPib(): ?string
    {
        return $this->pib;
    }

    /**
     * Set countryCode.
     *
     * @param string|null $countryCode
     *
     * @return Client
     */
    public function setCountryCode($countryCode = null): Client
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    /**
     * Get countryCode.
     *
     * @return string|null
     */
    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return Client
     */
    public function setCreatedAt($createdAt): Client
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
     * Set guarantor.
     *
     * @param Client|null $guarantor
     *
     * @return Client
     */
    public function setGuarantor(Client $guarantor = null): Client
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
