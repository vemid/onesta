<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Entity\Entity;

use Doctrine\ORM\Mapping as ORM;
use Vemid\ProjectOne\Entity\Entity;

/**
 * Stocks
 *
 * @ORM\Table(name="stocks", indexes={@ORM\Index(name="supplier_product_id", columns={"supplier_product_id"})})
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class Stock extends Entity
{
    public const INCOME = 'INCOME';
    public const OUTCOME = 'OUTCOME';
    /**
     * @var int
     *
     * @ORM\Column(name="qty", type="integer", nullable=false)
     */
    private $qty;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=0, nullable=false)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="purchase_price", type="decimal", precision=9, scale=2, nullable=false)
     */
    private $purchasePrice;

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
     * @var SupplierProduct
     *
     * @ORM\ManyToOne(targetEntity="SupplierProduct")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="supplier_product_id", referencedColumnName="id")
     * })
     */
    private $supplierProduct;


    /**
     * Set qty.
     *
     * @param int $qty
     *
     * @return Stock
     */
    public function setQty($qty)
    {
        $this->qty = $qty;

        return $this;
    }

    /**
     * Get qty.
     *
     * @return int
     */
    public function getQty()
    {
        return $this->qty;
    }

    /**
     * Set type.
     *
     * @param string $type
     *
     * @return Stock
     */
    public function setType($type): Stock
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     *
     * @return string
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * Set purchasePrice.
     *
     * @param string $purchasePrice
     *
     * @return Stock
     */
    public function setPurchasePrice($purchasePrice): Stock
    {
        $this->purchasePrice = $purchasePrice;

        return $this;
    }

    /**
     * Get purchasePrice.
     *
     * @return string
     */
    public function getPurchasePrice()
    {
        return $this->purchasePrice;
    }

    /**
     * @ORM\PrePersist
     * @return Stock
     * @throws \Exception
     */
    public function setCreatedAt(): Stock
    {
        $this->createdAt = new \DateTime();

        return $this;
    }

    /**
     * Get createdAt.
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
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
     * Set supplierProduct.
     *
     * @param SupplierProduct|null $supplierProduct
     *
     * @return Stock
     */
    public function setSupplierProduct(SupplierProduct $supplierProduct = null)
    {
        $this->supplierProduct = $supplierProduct;

        return $this;
    }

    /**
     * Get supplierProduct.
     *
     * @return SupplierProduct|null
     */
    public function getSupplierProduct()
    {
        return $this->supplierProduct;
    }
}
