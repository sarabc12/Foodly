<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Menu;
use App\Entity\Order;
use App\Entity\Restaurant;
use App\Repository\RestaurantRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class AdminSecurityService {

    private RestaurantRepository $restaurantRepository;

    public function __construct(RestaurantRepository $restaurantRepository)
    {
        $this->restaurantRepository = $restaurantRepository;
    }

    public function getRestaurantForUser(User $user): QueryBuilder {
        return $this->restaurantRepository->createByUserQueryBuilder($user);
    }

    /* checking that the user is a owner */
    public function checkRestaurantOwnership(Restaurant $restaurant, User $user): void 
    {
        if (!$restaurant->getUsers()->contains($user)) {
            throw new AccessDeniedException("You're not the owner of this restaurant.");
        }
    }


    public function checkMenuOwnership(Menu $menu, User $user): void {
        $ownsRestaurant = false;
        foreach($menu->getRestaurant()->getUsers() as $restaurantUsers) {
            if($restaurantUsers->getId() === $user->getId()) {
                $ownsRestaurant = true;
                break;
            }
        }
        if(!$ownsRestaurant){
            throw new AccessDeniedException("You do not have permission to access this Menu");
        }
    }

    public function canUserManageOrder(User $currentUser, Order $order) : bool {
        return $order->getUser() !== null;
    }
}