<?php

// src/DataFixtures/AppFixtures.php
namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Restaurant;
use App\Entity\Menu;
use App\Entity\Plate;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create(); 

        //CREATE 15 USERS
        for ($u = 1; $u <= 15; $u++) {
            $user = new User();
            $user->setName($faker->firstName);
            $user->setSurname($faker->lastName);
            $user->setEmail(strtolower($user->getName() . "." . $user->getSurname() . "@" . $faker->freeEmailDomain));
            $user->setPassword("password$u"); 

            if ($u === 1) {
                $user->setRoles(['ROLE_SUPER_ADMIN']);
            } elseif ($u <= 5) {
                $user->setRoles(['ROLE_OWNER']);
            } else {
                $user->setRoles(['ROLE_USER']);
            }

            $manager->persist($user);
        }


        $categories = [
            'Bakery & Pastry', 'Sushi Bar', 'Steakhouse', 'Gourmet Burger', 'Vegan Bistro', 
            'Italian Trattoria', 'Mexican Cantina', 'French Brasserie', 'Seafood Grill', 
            'Thai Kitchen', 'Indian Curry House', 'Mediterranean Mezze', 'Pizzeria Napoletana', 
            'Ramen Shop', 'Tapas Bar', 'BBQ Smokehouse', 'Fine Dining', 'Fusion Kitchen'
        ];

        $menuNames = [
            'Daily Specials', 'Chef Selection', 'Tasting Menu', 'Wine List', 'Kids Menu', 
            'Breakfast & Brunch', 'Business Lunch', 'Seasonal Spring Menu', 'Summer Delights', 
            'Autumn Harvest', 'Winter Comfort Food', 'Late Night Bites', 'Cocktail Pairings', 
            'Dessert Extravaganza', 'Gluten-Free Options', 'Signature Dishes'
        ];

        $foodNames = [
            'Grilled Salmon', 'Beef Wellington', 'Avocado Toast', 'Truffle Pasta', 'Cheesecake', 
            'Chicken Caesar', 'Ribeye Steak', 'Margherita Pizza', 'Spicy Tuna Roll', 'Eggs Benedict', 
            'Pork Belly Bao', 'Lamb Chops', 'Lobster Roll', 'Quinoa Salad', 'Duck Confit', 
            'Mushroom Risotto', 'Fish and Chips', 'Beef Tacos', 'Chicken Tikka Masala', 
            'Pad Thai', 'Falafel Wrap', 'Burrata with Pesto', 'BBQ Pork Ribs', 'Clam Chowder', 
            'Tiramisu', 'Profiteroles', 'Beef Tartare', 'Gazpacho', 'Dim Sum Selection', 
            'Pulled Pork Sandwich', 'Cauliflower Steak', 'Shrimp Scampi', 'Paella Valenciana'
        ];

        //CREATE 10 RESTAURANTS
        for ($r = 1; $r <= 20; $r++) {
            $restaurant = new Restaurant();
            $restaurant->setName($faker->company . " " . $faker->companySuffix);
            $restaurant->setCategory($faker->randomElement($categories));
            $manager->persist($restaurant);

            //CREATE 4 MENUS PER RESTAURANT
            for ($m = 1; $m <= 4; $m++) {
                $menu = new Menu();
                $menu->setName($faker->randomElement($menuNames));
                $menu->setDescription($faker->catchPhrase . "-" . $faker->bs);
                $menu->setRestaurant($restaurant);
                $manager->persist($menu);

            //CREATE 20 PLATES PER MENU
                for ($p = 1; $p <= 30; $p++) {
                    $plate = new Plate();
                    $plate->setName($faker->randomElement($foodNames) . " " . $faker->bs); // Adds a fancy marketing touch
                    $plate->setPrice($faker->randomFloat(2, 8, 60)); // Prices between 8.00 and 60.00
                    
                    $plate->addMenu($menu); 
                    $manager->persist($plate);
                }
            }
        }

        $manager->flush();
    }
}