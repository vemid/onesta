<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Entity\Entity;

use Doctrine\ORM\Mapping as ORM;
use Vemid\ProjectOne\Common\Annotation as FormAnnotation;
use Vemid\ProjectOne\Entity\Entity;

/**
 * Products
 *
 * @ORM\Table(name="products", indexes={@ORM\Index(name="code_id", columns={"code_id"})})
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Vemid\ProjectOne\Entity\Repository\ProductRepository")
 */
class Product extends Entity
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @FormAnnotation\FormElement(type="Hidden", required=true)
     */
    protected $id;

    /**
     * @var Code
     *
     * @ORM\ManyToOne(targetEntity="Code")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="code_id", referencedColumnName="id")
     * })
     * @FormAnnotation\FormElement(type="Select", required=true, relation="Code", name="Kategorija")
     */
    protected $code;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     * @FormAnnotation\FormElement(type="Text", required=true, name="Ime")
     */
    protected $name;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="text", length=0, nullable=true)
     * @FormAnnotation\FormElement(type="TextArea", required=false, name="Opis")
     */
    protected $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    protected $createdAt;

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return Product
     */
    public function setName($name): Product
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
     * Set description.
     *
     * @param string|null $description
     *
     * @return Product
     */
    public function setDescription($description = null): Product
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     *
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @ORM\PrePersist
     * @return Product
     * @throws \Exception
     */
    public function setCreatedAt(): Product
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
     * Set code.
     *
     * @param Code|null $code
     *
     * @return Product
     */
    public function setCode($code = null): Product
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
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }
}
