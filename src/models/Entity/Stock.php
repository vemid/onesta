<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Entity\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\InheritanceType;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Vemid\ProjectOne\Entity\Entity;

/**
 * Stocks
 *
 * @ORM\Table(name="stocks", indexes={@ORM\Index(name="supplier_product_id", columns={"supplier_product_id"})})
 * @ORM\Entity
 * @InheritanceType("JOINED")
 * @DiscriminatorColumn(name="entity_type", type="string")
 * @DiscriminatorMap({"stock" = "Stock", "supplierReceiptItem" = "SupplierReceiptItem", "purchaseItem" = "PurchaseItem"})
 * @ORM\HasLifecycleCallbacks()
 */
class Stock extends Entity
{
    public const INCOME = 'INCOME';
    public const OUTCOME = 'OUTCOME';

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=0, nullable=false)
     */
    private $type;

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

//    /**
//     * @return string
//     */
//    public function getEntityType(): string
//    {
//        return $this->entityType;
//    }
//
//    /**
//     * @param string $entityType
//     * @return Stock
//     */
//    public function setEntityType(string $entityType): Stock
//    {
//        $this->entityType = $entityType;
//
//        return $this;
//    }


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
