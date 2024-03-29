<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Entity\Entity;

use Doctrine\ORM\Mapping as ORM;
use Vemid\ProjectOne\Common\Annotation as FormAnnotation;

/**
 * SupplierReceiptItems
 *
 * @ORM\Table(name="supplier_receipt_items", indexes={@ORM\Index(name="product_id", columns={"product_id"}), @ORM\Index(name="supplier_receipt_id", columns={"supplier_receipt_id"})})
 * @ORM\Entity(repositoryClass="Vemid\ProjectOne\Entity\Repository\SupplierReceiptItemRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class SupplierReceiptItem extends Stock
{
    /**
     * @var SupplierReceipt
     *
     * @ORM\ManyToOne(targetEntity="SupplierReceipt")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="supplier_receipt_id", referencedColumnName="id")
     * })
     * @FormAnnotation\FormElement(type="Hidden", required=true, name="Prijemnica")
     */
    private $supplierReceipt;

    /**
     * @var Product
     *
     * @ORM\ManyToOne(targetEntity="Product")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     * })
     * @FormAnnotation\FormElement(type="Select", required=true, relation="Product", name="Proizvod")
     */
    private $product;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="decimal", precision=9, scale=2, nullable=false)
     * @FormAnnotation\FormElement(type="Text", required=true, name="Prodajna Cena")
     */
    private $price;

    /**
     * @var float
     *
     * @ORM\Column(name="retail_price", type="decimal", precision=9, scale=2, nullable=false)
     * @FormAnnotation\FormElement(type="Text", required=true, name="Nabavna Cena")
     */
    private $retailPrice;

    /**
     * @var int
     *
     * @ORM\Column(name="qty", type="integer", nullable=false)
     * @FormAnnotation\FormElement(type="Text", required=true, name="Količina")
     */
    private $qty;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @param float $price
     * @return SupplierReceiptItem
     */
    public function setPrice($price): SupplierReceiptItem
    {
        $this->price = (float)$price;

        return $this;
    }

    /**
     * Get price.
     *
     * @return float
     */
    public function getPrice(): ?float
    {
        return (float)$this->price;
    }

    /**
     * @param float $retailPrice
     * @return SupplierReceiptItem
     */
    public function setRetailPrice($retailPrice): SupplierReceiptItem
    {
        $this->retailPrice = (float)$retailPrice;

        return $this;
    }

    /**
     * Get price.
     *
     * @return float
     */
    public function getRetailPrice(): ?float
    {
        return (float)$this->retailPrice;
    }

    /**
     * @param $qty
     * @return SupplierReceiptItem|null
     */
    public function setQty($qty): ?SupplierReceiptItem
    {
        $this->qty = $qty;

        return $this;
    }

    /**
     * Get qty.
     *
     * @return int
     */
    public function getQty(): ?int
    {
        return (int)$this->qty;
    }

    /**
     * @return SupplierReceiptItem|null
     * @throws \Exception
     * @ORM\PrePersist
     */
    public function setCreatedAt()
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
     * @param SupplierReceipt|null $supplierReceipt
     * @return SupplierReceiptItem
     */
    public function setSupplierReceipt(SupplierReceipt $supplierReceipt = null): SupplierReceiptItem
    {
        $this->supplierReceipt = $supplierReceipt;

        return $this;
    }

    /**
     * Get supplierReceipt.
     *
     * @return SupplierReceipt|null
     */
    public function getSupplierReceipt(): ?SupplierReceipt
    {
        return $this->supplierReceipt;
    }

    /**
     * @param Product|null $product
     * @return SupplierReceiptItem
     */
    public function setProduct(Product $product = null): ?SupplierReceiptItem
    {
        $this->product = $product;

        return $this;
    }

    /**
     * @return Product|null
     */
    public function getProduct(): ?Product
    {
        return $this->product;
    }
}
