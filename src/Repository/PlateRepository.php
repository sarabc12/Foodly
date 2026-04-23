<?php

namespace App\Repository;

use App\Entity\Plate;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Plate>
 */
class PlateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Plate::class);
    }

    public function filterByUser(QueryBuilder $queryBuilder, User  $user): QueryBuilder {
        return $queryBuilder
            ->join('entity.menus', 'm')
            ->join('m.restaurant', 'r')
            ->join('r.users', 'u')
            ->andWhere('u.id = :userId')
            ->setParameter('userId', $user->getId());
    }

}
