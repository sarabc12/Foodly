<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\MenuRepository;

class MenuLayoutService {
    private MenuRepository $menuRepository;

    public function __construct(MenuRepository $menuRepository)
    {
        $this->menuRepository = $menuRepository;
    }

    public function getMenuGroupedByRestaurant(User $user) : array {
        $qb = $this->menuRepository->createQueryBuilder('entity');
        $menus = $this->menuRepository->filterByUser($qb, $user)
            ->getQuery()
            ->getResult();

        $grouped = [];
        foreach ($menus as $menu){
            $restaurantName = $menu->getRestaurant()?->getName() ?? 'no restaurant';
            $grouped[$restaurantName][] = $menu;
        }
        return $grouped;
    }
}