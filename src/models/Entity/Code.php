<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Entity\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Codes
 *
 * @ORM\Table(name="codes", uniqueConstraints={@ORM\UniqueConstraint(name="code", columns={"code"})}, indexes={@ORM\Index(name="code_type_id", columns={"code_type_id"}), @ORM\Index(name="parent_id", columns={"parent_id"})})
 * @ORM\Entity
 */
class Code
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
     * @ORM\Column(name="code", type="string", length=255, nullable=false)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var CodeType
     *
     * @ORM\ManyToOne(targetEntity="CodeType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="code_type_id", referencedColumnName="id")
     * })
     */
    private $codeType;

    /**
     * @var Code
     *
     * @ORM\ManyToOne(targetEntity="Code")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     * })
     */
    private $parent;


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
     * Set code.
     *
     * @param string $code
     *
     * @return Code
     */
    public function setCode($code): Code
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code.
     *
     * @return string
     */
    public function getCode(): string
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
    public function getName(): string
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
    public function getCodeType(): CodeType
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
}
