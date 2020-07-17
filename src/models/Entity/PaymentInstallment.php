<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Entity\Entity;

use Doctrine\ORM\Mapping as ORM;
use Vemid\ProjectOne\Common\Annotation as FormAnnotation;
use Vemid\ProjectOne\Entity\Entity;

/**
 * PaymentInstallments
 *
 * @ORM\Table(name="payment_installments", indexes={@ORM\Index(name="purchase_id", columns={"purchase_id"})})
 * @ORM\Entity
 */
class PaymentInstallment extends Entity
{
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="installment_date", type="datetime", nullable=false)
     * @FormAnnotation\FormElement(type="Date", required=true)
     */
    private $installmentDate;

    /**
     * @var string
     *
     * @ORM\Column(name="installment_amount", type="decimal", precision=9, scale=2, nullable=false)
     * @FormAnnotation\FormElement(type="Text", required=true)
     */
    private $installmentAmount;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="payment_date", type="datetime", nullable=true)
     * @FormAnnotation\FormElement(type="Date", required=false)
     */
    private $paymentDate;

    /**
     * @var string|null
     *
     * @ORM\Column(name="payment_amount", type="decimal", precision=9, scale=2, nullable=true)
     * @FormAnnotation\FormElement(type="Text", required=false)
     */
    private $paymentAmount;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var Purchase
     *
     * @ORM\ManyToOne(targetEntity="Purchase")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="purchase_id", referencedColumnName="id")
     * })
     */
    private $purchase;


    /**
     * Set installmentDate.
     *
     * @param \DateTime $installmentDate
     *
     * @return PaymentInstallment
     */
    public function setInstallmentDate($installmentDate)
    {
        $this->installmentDate = $installmentDate;

        return $this;
    }

    /**
     * Get installmentDate.
     *
     * @return \DateTime
     */
    public function getInstallmentDate()
    {
        return $this->installmentDate;
    }

    /**
     * Set installmentAmount.
     *
     * @param string $installmentAmount
     *
     * @return PaymentInstallment
     */
    public function setInstallmentAmount($installmentAmount)
    {
        $this->installmentAmount = $installmentAmount;

        return $this;
    }

    /**
     * Get installmentAmount.
     *
     * @return string
     */
    public function getInstallmentAmount()
    {
        return $this->installmentAmount;
    }

    /**
     * Set paymentDate.
     *
     * @param \DateTime|null $paymentDate
     *
     * @return PaymentInstallment
     */
    public function setPaymentDate($paymentDate = null)
    {
        $this->paymentDate = $paymentDate;

        return $this;
    }

    /**
     * Get paymentDate.
     *
     * @return \DateTime|null
     */
    public function getPaymentDate()
    {
        return $this->paymentDate;
    }

    /**
     * Set paymentAmount.
     *
     * @param string|null $paymentAmount
     *
     * @return PaymentInstallment
     */
    public function setPaymentAmount($paymentAmount = null)
    {
        $this->paymentAmount = $paymentAmount;

        return $this;
    }

    /**
     * Get paymentAmount.
     *
     * @return string|null
     */
    public function getPaymentAmount()
    {
        return $this->paymentAmount;
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set purchase.
     *
     * @param Purchase|null $purchase
     *
     * @return PaymentInstallment
     */
    public function setPurchase(Purchase $purchase = null)
    {
        $this->purchase = $purchase;

        return $this;
    }

    /**
     * Get purchase.
     *
     * @return Purchase|null
     */
    public function getPurchase()
    {
        return $this->purchase;
    }
}
