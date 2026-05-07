<?php

namespace App\DataFixtures;

use App\Entity\OrderItem;
use App\Entity\Order;
use App\Entity\Plate;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class OrderItemFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        $orders = $manager->getRepository(Order::class)->findAll();
        $plates = $manager->getRepository(Plate::class)->findAll();

        echo "Inizio caricamento OrderItems...\n";

        foreach ($orders as $order) {
                    $numberOfPlates = $faker->numberBetween(2, 5);
                    for ($i = 0; $i < $numberOfPlates; $i++) {
                        $orderItem = new OrderItem();
                        $orderItem->setOrder($order);
                        $orderItem->setPlate($faker->randomElement($plates));

                        $manager->persist($orderItem);
                    }
        }
        

        $manager->flush();
        $manager->clear(); 
    }
}