<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Entity\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PurchaseItems
 *
 * @ORM\Table(name="purchase_items", indexes={@ORM\Index(name="purchase_id", columns={"purchase_id"}), @ORM\Index(name="supplier_product_id", columns={"supplier_product_id"})})
 * @ORM\Entity
 */
class PurchaseItem
{
    /**
     * @var float
     *
     * @ORM\Column(name="price", type="decimal", precision=9, scale=2, nullable=false)
     */
    private $price;

    /**
     * @var int
     *
     * @ORM\Column(name="qty", type="integer", nullable=false)
     */
    private $qty;

    /**
     * @var string|null
     *
     * @ORM\Column(name="note_1", type="text", length=0, nullable=true)
     */
    private $note1;

    /**
     * @var string|null
     *
     * @ORM\Column(name="note_2", type="text", length=0, nullable=true)
     */
    private $note2;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

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
     * @var SupplierProduct
     *
     * @ORM\ManyToOne(targetEntity="SupplierProduct")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="supplier_product_id", referencedColumnName="id")
     * })
     */
    private $supplierProduct;


    /**
     * Set price.
     *
     * @param string $price
     *
     * @return PurchaseItem
     */
    public function setPrice($price): PurchaseItem
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price.
     *
     * @return float
     */
    public function getPrice(): ?float
    {
        return $this->price;
    }

    /**
     * Set qty.
     *
     * @param int $qty
     *
     * @return PurchaseItem
     */
    public function setQty($qty): PurchaseItem
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
        return $this->qty;
    }

    /**
     * Set note1.
     *
     * @param string|null $note1
     *
     * @return PurchaseItem
     */
    public function setNote1($note1 = null): PurchaseItem
    {
        $this->note1 = $note1;

        return $this;
    }

    /**
     * Get note1.
     *
     * @return string|null
     */
    public function getNote1(): ?string
    {
        return $this->note1;
    }

    /**
     * Set note2.
     *
     * @param string|null $note2
     *
     * @return PurchaseItem
     */
    public function setNote2($note2 = null): PurchaseItem
    {
        $this->note2 = $note2;

        return $this;
    }

    /**
     * Get note2.
     *
     * @return string|null
     */
    public function getNote2(): ?string
    {
        return $this->note2;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return PurchaseItem
     */
    public function setCreatedAt($createdAt): PurchaseItem
    {
        $this->createdAt = $createdAt;

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
     * Set purchase.
     *
     * @param Purchase|null $purchase
     *
     * @return PurchaseItem
     */
    public function setPurchase(Purchase $purchase = null): PurchaseItem
    {
        $this->purchase = $purchase;

        return $this;
    }

    /**
     * Get purchase.
     *
     * @return Purchase|null
     */
    public function getPurchase(): ?Purchase
    {
        return $this->purchase;
    }

    /**
     * Set supplierProduct.
     *
     * @param SupplierProduct|null $supplierProduct
     *
     * @return PurchaseItem
     */
    public function setSupplierProduct(SupplierProduct $supplierProduct = null): PurchaseItem
    {
        $this->supplierProduct = $supplierProduct;

        return $this;
    }

    /**
     * Get supplierProduct.
     *
     * @return SupplierProduct|null
     */
    public function getSupplierProduct(): ?SupplierProduct
    {
        return $this->supplierProduct;
    }
}
