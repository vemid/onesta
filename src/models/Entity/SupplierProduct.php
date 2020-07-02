<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Entity\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Vemid\ProjectOne\Entity\Entity;

/**
 * SupplierProducts
 *
 * @ORM\Table(name="supplier_products", indexes={@ORM\Index(name="product_id", columns={"product_id"}), @ORM\Index(name="supplier_id", columns={"supplier_id"})})
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class SupplierProduct extends Entity
{
    /**
     * @var float
     *
     * @ORM\Column(name="avg_purchase_price", type="decimal", precision=9, scale=2, nullable=false)
     */
    protected $avgPurchasePrice;

    /**
     * @var float
     *
     * @ORM\Column(name="retail_price", type="decimal", precision=9, scale=2, nullable=false)
     */
    protected $retailPrice;

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
     * @var Product
     *
     * @ORM\ManyToOne(targetEntity="Product")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     * })
     */
    protected $product;

    /**
     * @var Supplier
     *
     * @ORM\ManyToOne(targetEntity="Supplier")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="supplier_id", referencedColumnName="id")
     * })
     */
    protected $supplier;

    /**
     * @ORM\OneToMany(targetEntity="Stock", mappedBy="supplierProduct", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    protected $stocks;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->stocks = new ArrayCollection();
    }


    /**
     * Set avgPurchasePrice.
     *
     * @param float $avgPurchasePrice
     *
     * @return SupplierProduct
     */
    public function setAvgPurchasePrice($avgPurchasePrice): SupplierProduct
    {
        $this->avgPurchasePrice = $avgPurchasePrice;

        return $this;
    }

    /**
     * Get avgPurchasePrice.
     *
     * @return float
     */
    public function getAvgPurchasePrice(): ?float
    {
        return $this->avgPurchasePrice;
    }

    /**
     * Set retailPrice.
     *
     * @param float $retailPrice
     *
     * @return SupplierProduct
     */
    public function setRetailPrice($retailPrice): SupplierProduct
    {
        $this->retailPrice = $retailPrice;

        return $this;
    }

    /**
     * Get retailPrice.
     *
     * @return float
     */
    public function getRetailPrice(): ?float
    {
        return $this->retailPrice;
    }

    /**
     * Set createdAt.
     *
     * @ORM\PrePersist
     * @return SupplierProduct
     * @throws \Exception
     */
    public function setCreatedAt(): SupplierProduct
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
     * Set product.
     *
     * @param Product|null $product
     *
     * @return SupplierProduct
     */
    public function setProduct(Product $product = null): SupplierProduct
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product.
     *
     * @return Product|null
     */
    public function getProduct(): ?Product
    {
        return $this->product;
    }

    /**
     * Set supplier.
     *
     * @param Supplier|null $supplier
     *
     * @return SupplierProduct
     */
    public function setSupplier(Supplier $supplier = null): SupplierProduct
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

    /**
     * @return PersistentCollection
     */
    public function getStocks(): ?PersistentCollection
    {
        return $this->stocks;
    }
}
