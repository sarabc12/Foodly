<?php

namespace App\DataFixtures;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\Plate;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class OrderItemsFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        $orders = $manager->getRepository(Order::class)->findAll();
        $plates = $manager->getRepository(Plate::class)->findAll();

        if (empty($orders) || empty($plates)) {
            return; 
        }

        foreach ($orders as $order) {
            for ($i = 0; $i < 3; $i++) {
                $orderItem = new OrderItem();
                $orderItem->setRelatedOrder($order);

                $randomPlates = $faker->randomElements($plates, 3);
                foreach ($randomPlates as $plate) {
                    $orderItem->addPlate($plate);
                }

                $manager->persist($orderItem);
            }
        }

        $manager->flush();
    }
}