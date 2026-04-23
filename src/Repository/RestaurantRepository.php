<?php

namespace App\Repository;

use App\Entity\Restaurant;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Restaurant>
 */
class RestaurantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Restaurant::class);
    }

    public function createByUserQueryBuilder(User $user): QueryBuilder{
        return $this->createQueryBuilder('r')
        ->join('r.users', 'u')
        ->andWhere('u.id = :userId')
        ->setParameter('userId', $user->getId());
    }
}
