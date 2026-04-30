<?php

namespace App\Repository;

use App\Entity\Order;
use App\Entity\OrderItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OrderItem>
 */
class OrderItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderItem::class);
    }

    public function findItemsByOrderPlates(Order $order): array{
        return $this->createQueryBuilder('oi')
            ->leftJoin('oi.plate', 'p')
            ->addSelect('p')
            ->andWhere('oi.relatedOrder = :order')
            ->setParameter('order', $order)
            ->getQuery()
            ->getResult();
    }
}
