<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Entity\Entity;

use Doctrine\ORM\Mapping as ORM;
use Vemid\ProjectOne\Common\Annotation as FormAnnotation;
use Vemid\ProjectOne\Entity\Entity;

/**
 * SupplierReceipts
 *
 * @ORM\Table(name="supplier_receipts", indexes={@ORM\Index(name="supplier_id", columns={"supplier_id"})})
 * @ORM\Entity(repositoryClass="Vemid\ProjectOne\Entity\Repository\SupplierReceiptRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class SupplierReceipt extends Entity
{
    /**
     * @var Supplier
     *
     * @ORM\ManyToOne(targetEntity="Supplier")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="supplier_id", referencedColumnName="id")
     * })
     * @FormAnnotation\FormElement(type="Select", required=true, relation="Supplier")
     */
    protected $supplier;

    /**
     * @var string
     *
     * @ORM\Column(name="file", type="string", length=255, nullable=false)
     * @FormAnnotation\FormElement(type="Upload", required=false)
     */
    protected $file;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=false)
     * @FormAnnotation\FormElement(type="Date", required=true)
     */
    protected $date;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="payment_date", type="datetime", nullable=false)
     * @FormAnnotation\FormElement(type="Date", required=true)
     */
    protected $paymentDate;

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
     * @param string $file
     * @return SupplierReceipt
     */
    public function setFile($file): SupplierReceipt
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file.
     *
     * @return string
     */
    public function getFile(): ?string
    {
        return $this->file;
    }

    /**
     * @param $date
     * @return $this
     * @throws \Exception
     */
    public function setDate($date)
    {
        $this->date = new \DateTime($date);

        return $this;
    }

    /**
     * Get date.
     *
     * @return \DateTime
     */
    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    /**
     * @param $date
     * @return $this
     * @throws \Exception
     */
    public function sePaymentDate($date)
    {
        $this->paymentDate = new \DateTime($date);

        return $this;
    }

    /**
     * Get date.
     *
     * @return \DateTime
     */
    public function getPaymentDate(): ?\DateTime
    {
        return $this->paymentDate;
    }

    /**
     * @ORM\PrePersist
     * @return SupplierReceipt
     * @throws \Exception
     */
    public function setCreatedAt(): SupplierReceipt
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
     *     * @ORM\PrePersist

     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Set supplier.
     *
     * @param Supplier|null $supplier
     *
     * @return SupplierReceipt
     */
    public function setSupplier(Supplier $supplier = null): SupplierReceipt
    {
        $this->supplier = $supplier;

        return $this;
    }

    /**
     * Get supplier.
     *
     * @return Supplier|null
     */
    public function getSupplier(): ?Supplier
    {
        return $this->supplier;
    }
}
