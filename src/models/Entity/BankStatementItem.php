<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Entity\Entity;

use Doctrine\ORM\Mapping as ORM;
use Vemid\ProjectOne\Entity\Entity;

/**
 * BankStatementItems
 *
 * @ORM\Table(name="bank_statement_items", indexes={@ORM\Index(name="bank_statement_id", columns={"bank_statement_id"})})
 * @ORM\Entity
 */
class BankStatementItem extends Entity
{
    /**
     * @var string
     *
     * @ORM\Column(name="company_name", type="string", length=255, nullable=false)
     */
    private $companyName;

    /**
     * @var string
     *
     * @ORM\Column(name="account", type="string", length=255, nullable=false)
     */
    private $account;

    /**
     * @var float
     *
     * @ORM\Column(name="amount", type="float", precision=10, scale=0, nullable=false)
     */
    private $amount;

    /**
     * @var string
     *
     * @ORM\Column(name="purpose", type="string", length=255, nullable=false)
     */
    private $purpose;

    /**
     * @var string
     *
     * @ORM\Column(name="reference_number", type="string", length=255, nullable=false)
     */
    private $referenceNumber;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var BankStatement
     *
     * @ORM\ManyToOne(targetEntity="Vemid\ProjectOne\Entity\Entity\BankStatement")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="bank_statement_id", referencedColumnName="id")
     * })
     */
    private $bankStatement;

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
     * Set companyName.
     *
     * @param string $companyName
     *
     * @return BankStatementItem
     */
    public function setCompanyName($companyName): BankStatementItem
    {
        $this->companyName = $companyName;

        return $this;
    }

    /**
     * Get companyName.
     *
     * @return string
     */
    public function getCompanyName(): string
    {
        return $this->companyName;
    }

    /**
     * Set account.
     *
     * @param string $account
     *
     * @return BankStatementItem
     */
    public function setAccount($account): BankStatementItem
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
     * Set amount.
     *
     * @param float $amount
     *
     * @return BankStatementItem
     */
    public function setAmount($amount): BankStatementItem
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount.
     *
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * Set purpose.
     *
     * @param string $purpose
     *
     * @return BankStatementItem
     */
    public function setPurpose($purpose): BankStatementItem
    {
        $this->purpose = $purpose;

        return $this;
    }

    /**
     * Get purpose.
     *
     * @return string
     */
    public function getPurpose(): string
    {
        return $this->purpose;
    }

    /**
     * Set referenceNumber.
     *
     * @param string $referenceNumber
     *
     * @return BankStatementItem
     */
    public function setReferenceNumber($referenceNumber): BankStatementItem
    {
        $this->referenceNumber = $referenceNumber;

        return $this;
    }

    /**
     * Get referenceNumber.
     *
     * @return string
     */
    public function getReferenceNumber(): string
    {
        return $this->referenceNumber;
    }

    /**
     * Set bankStatement.
     *
     * @param BankStatement|null $bankStatement
     *
     * @return BankStatementItem
     */
    public function setBankStatement(BankStatement $bankStatement = null): BankStatementItem
    {
        $this->bankStatement = $bankStatement;

        return $this;
    }

    /**
     * Get bankStatement.
     *
     * @return BankStatement|null
     */
    public function getBankStatement(): BankStatement
    {
        return $this->bankStatement;
    }
}
