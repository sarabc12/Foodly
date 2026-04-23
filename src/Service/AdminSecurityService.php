<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Menu;
use App\Entity\Restaurant;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class AdminSecurityService {

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
}