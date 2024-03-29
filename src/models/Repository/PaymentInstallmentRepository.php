<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Vemid\ProjectOne\Entity\Entity\PaymentInstallment;
use Vemid\ProjectOne\Entity\Entity\Purchase;

/**
 * Class PaymentInstallmentRepository
 * @package Vemid\ProjectOne\Entity\Repository
 */
class PaymentInstallmentRepository extends EntityRepository
{
    public function fetchTotalInstalmentPrice(Purchase $purchase)
    {
        $query = $this->getEntityManager()->createQueryBuilder()
            ->select('SUM(p.installmentAmount) as totalPrice')
            ->from(PaymentInstallment::class, 'p')
            ->where('p.purchase = :purchase')
            ->setParameters([
                'purchase' => $purchase->getId(),
            ])
            ->getQuery()
            ->execute();

        $price = 0;
        if (!empty($query[0]['totalPrice'])) {
            $price = $query[0]['totalPrice'];
        }

        return $price;
    }

    /**
     * @param Purchase $purchase
     * @return PaymentInstallment[]
     */
    public function fetchMissingInstallments(Purchase $purchase)
    {
        return $this->getEntityManager()->createQueryBuilder()
            ->select('pi')
            ->from(PaymentInstallment::class, 'pi')
            ->where('pi.purchase = :purchase')
            ->andWhere('pi.paymentDate IS NULL OR pi.paymentAmount < 1')
            ->andWhere('pi.installmentDate < CURDATE()')
            ->setParameters([
                'purchase' => $purchase->getId(),
            ])
            ->getQuery()
            ->execute();
    }

    /**
     * @param Purchase $purchase
     * @return int|float
     */
    public function fetchInstallmentsShouldPayToday(Purchase $purchase)
    {
        return $this->getEntityManager()->createQueryBuilder()
            ->select('pi')
            ->from(PaymentInstallment::class, 'pi')
            ->where('pi.purchase = :purchase')
            ->andWhere('pi.paymentAmount < 1')
            ->andWhere('DATE(pi.installmentDate) = CURDATE()')
            ->setParameters([
                'purchase' => $purchase->getId(),
            ])
            ->getQuery()
            ->execute();
    }
}
