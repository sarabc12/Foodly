<?php

namespace App\Repository;

use App\Entity\Menu;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<Menu>
 */
class MenuRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Menu::class);
    }
        /* definire la query */
    public function filterByUser(QueryBuilder $queryBuilder, User $user): QueryBuilder {
        return $queryBuilder
            ->join('entity.restaurant', 'r')
            ->join('r.users', 'u')
            ->andWhere('u.id = :userId')
            ->setParameter('userId', $user->getId());
    }
}
