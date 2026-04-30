<?php

namespace App\DataFixtures;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\Plate;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class OrderItemsFixtures extends Fixture implements DependentFixtureInterface
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
            $numberOfItems = rand(1, 3);

            for ($i = 0; $i < $numberOfItems; $i++) {
                $orderItem = new OrderItem();
                $orderItem->setRelatedOrder($order);

                $randomPlates = $faker->randomElements($plates, rand(1, 2));
                
                foreach ($randomPlates as $plate) {
                    $orderItem->addPlate($plate);
                }

                $manager->persist($orderItem);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            AppFixtures::class, 
        ];
    }
}