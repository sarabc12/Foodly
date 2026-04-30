<?php

namespace App\DataFixtures;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\Plate;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

// Usiamo DependentFixtureInterface per essere sicuri che Order e Plate esistano già
class OrderItemsFixtures extends Fixture 
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        
        // Recuperiamo gli ordini e i piatti esistenti nel DB
        $orders = $manager->getRepository(Order::class)->findAll();
        $plates = $manager->getRepository(Plate::class)->findAll();

        if (empty($orders) || empty($plates)) {
            return; 
        }

        foreach ($orders as $order) {
            // Per ogni ordine, creiamo da 1 a 4 righe d'ordine (OrderItem)
            $numberOfItems = rand(1, 4);
            $randomPlates = $faker->randomElements($plates, $numberOfItems);

            foreach ($randomPlates as $plate) {
                $orderItem = new OrderItem();
                $orderItem->setRelatedOrder($order);
                $orderItem->setPlate($plate);
                // Se hai aggiunto la quantità nell'entity, setta anche quella:
                // $orderItem->setQuantity(rand(1, 3)); 

                $manager->persist($orderItem);
            }
        }

        $manager->flush();
    }
}