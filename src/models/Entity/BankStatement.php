<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Entity\Entity;

use Doctrine\ORM\Mapping as ORM;
use Vemid\ProjectOne\Entity\Entity;

/**
 * BankStatements
 *
 * @ORM\Table(name="bank_statements")
 * @ORM\Entity
 */
class BankStatement extends Entity
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="account", type="string", length=255, nullable=false)
     */
    private $account;

    /**
     * @var string
     *
     * @ORM\Column(name="statement_number", type="string", length=255, nullable=false)
     */
    private $statementNumber;

    /**
     * @var string|null
     *
     * @ORM\Column(name="statement_path", type="string", length=255, nullable=true)
     */
    private $statementPath;

    /**
     * @var string|null
     *
     * @ORM\Column(name="file_hash", type="string", length=255, nullable=true)
     */
    private $fileHash;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=false)
     */
    private $date;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

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
     * Set account.
     *
     * @param string $account
     *
     * @return BankStatement
     */
    public function setAccount($account): BankStatement
    {
        $this->account = $account;

        return $this;
    }

    /**
     * Get account.
     *
     * @return string
     */
    public function getAccount(): string
    {
        return $this->account;
    }

    /**
     * Set statementNumber.
     *
     * @param string $statementNumber
     *
     * @return BankStatement
     */
    public function setStatementNumber($statementNumber): BankStatement
    {
        $this->statementNumber = $statementNumber;

        return $this;
    }

    /**
     * Get statementNumber.
     *
     * @return string
     */
    public function getStatementNumber(): string
    {
        return $this->statementNumber;
    }

    /**
     * Set statementPath.
     *
     * @param string|null $statementPath
     *
     * @return BankStatement
     */
    public function setStatementPath($statementPath = null): BankStatement
    {
        $this->statementPath = $statementPath;

        return $this;
    }

    /**
     * Get statementPath.
     *
     * @return string|null
     */
    public function getStatementPath(): ?string
    {
        return $this->statementPath;
    }

    /**
     * Set fileHash.
     *
     * @param string|null $fileHash
     *
     * @return BankStatement
     */
    public function setFileHash($fileHash = null): BankStatement
    {
        $this->fileHash = $fileHash;

        return $this;
    }

    /**
     * Get fileHash.
     *
     * @return string|null
     */
    public function getFileHash(): ?string
    {
        return $this->fileHash;
    }

    /**
     * Set date.
     *
     * @param \DateTime $date
     *
     * @return BankStatement
     */
    public function setDate($date): BankStatement
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date.
     *
     * @return \DateTime
     */
    public function getDate(): \DateTime
    {
        return $this->date;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return BankStatement
     */
    public function setCreatedAt($createdAt): BankStatement
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
}
