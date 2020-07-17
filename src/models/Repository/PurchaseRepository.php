<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Vemid\ProjectOne\Entity\Entity\Purchase;
use Vemid\ProjectOne\Entity\Entity\PurchaseItem;
use Vemid\ProjectOne\Entity\Entity\Registration;

/**
 * Class PurchaseRepository
 * @package Vemid\ProjectOne\Entity\Repository
 */
class PurchaseRepository extends EntityRepository
{
    /**
     * @param Purchase $purchase
     * @return int|float
     */
    public function fetchTotalPrice(Purchase $purchase)
    {
        $totalPrice = $this->getEntityManager()->createQueryBuilder()
            ->select('SUM(pi.price * pi.qty) as totalPrice')
            ->from(Purchase::class, 'p')
            ->leftJoin(PurchaseItem::class, 'pi', Join::WITH, 'p.id = pi.purchase')
            ->where('p.id = :purchase')
            ->setParameters([
                'purchase' => $purchase->getId(),
            ])
            ->getQuery()
            ->execute();

        $price = 0;
        if (!empty($totalPrice[0]['totalPrice'])) {
            $price = $totalPrice[0]['totalPrice'];
        }

        $totalPriceRegistration = $this->getEntityManager()->createQueryBuilder()
            ->select('SUM(r.price) as totalPrice')
            ->from(Purchase::class, 'p')
            ->leftJoin(Registration::class, 'r', Join::WITH, 'p.id = r.purchase')
            ->where('p.id = :purchase')
            ->setParameters([
                'purchase' => $purchase->getId(),
            ])
            ->getQuery()
            ->execute();

        if (!empty($totalPriceRegistration[0]['totalPrice'])) {
            $price += $totalPriceRegistration[0]['totalPrice'];
        }

        return $price;
    }
}