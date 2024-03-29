<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Entity\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Vemid\ProjectOne\Common\Annotation as FormAnnotation;
use Vemid\ProjectOne\Common\Misc\StringToCode;
use Vemid\ProjectOne\Entity\Entity;

/**
 * Codes
 *
 * @ORM\Table(name="codes", uniqueConstraints={@ORM\UniqueConstraint(name="code", columns={"code"})}, indexes={@ORM\Index(name="code_type_id", columns={"code_type_id"}), @ORM\Index(name="parent_id", columns={"parent_id"})})
 * @ORM\Entity(repositoryClass="Vemid\ProjectOne\Entity\Repository\CodeRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Code extends Entity
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=255, nullable=false)
     */
    protected $code;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     * @FormAnnotation\FormElement(type="Text", required=true)
     */
    protected $name;

    /**
     * @var CodeType
     *
     * @ORM\ManyToOne(targetEntity="CodeType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="code_type_id", referencedColumnName="id")
     * })
     * @FormAnnotation\FormElement(type="Select", required=true, relation="CodeType", name="Tip")
     */
    private $codeType;

    /**
     * @var Code
     *
     * @ORM\ManyToOne(targetEntity="Code", inversedBy="children")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     * })
     * @FormAnnotation\FormElement(type="Select", required=false, relation="Code", name="Pod kategorija")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="Code", mappedBy="parent")
     */
    private $children;

    /**
     * Code constructor.
     */
    public function __construct()
    {
        $this->children = new ArrayCollection();
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
     * @ORM\PrePersist
     * @return Code
     */
    public function setCode(): Code
    {
        $this->code = StringToCode::filter($this->name);

        return $this;
    }

    /**
     * Get code.
     *
     * @return string
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return Code
     */
    public function setName($name): Code
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
     * Set codeType.
     *
     * @param CodeType|null $codeType
     *
     * @return Code
     */
    public function setCodeType(CodeType $codeType = null): Code
    {
        $this->codeType = $codeType;

        return $this;
    }

    /**
     * Get codeType.
     *
     * @return CodeType|null
     */
    public function getCodeType(): ?CodeType
    {
        return $this->codeType;
    }

    /**
     * Set parent.
     *
     * @param Code|null $parent
     *
     * @return Code
     */
    public function setParent(Code $parent = null): Code
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent.
     *
     * @return Code|null
     */
    public function getParent(): ?Code
    {
        return $this->parent;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->getName();
    }
}
