<?php

// src/DataFixtures/OrderItemFixtures.php
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

        if (empty($orders) || empty($plates)) {
            echo "Attenzione: Assicurati di avere già ordini e piatti nel database!\n";
            return;
        }

        foreach ($orders as $order) {
            $numberOfPlates = $faker->numberBetween(2, 6);
            
            for ($i = 0; $i < $numberOfPlates; $i++) {
                $orderItem = new OrderItem();
                $orderItem->setOrderId($order);
                $orderItem->setPlateId($faker->randomElement($plates));

                $manager->persist($orderItem);
            }
        }

        $manager->flush();
    }
}