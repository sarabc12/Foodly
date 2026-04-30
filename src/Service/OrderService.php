<?php

namespace App\Service;

use App\Entity\Order;
use App\Repository\OrderItemRepository;

class OrderService {

    public function __construct(private OrderItemRepository $orderItemRepository){

    }
    
    public function getAvaiableStatuses(): array {
        return  [
            'Pending'  => 'pending',
            'Canceled'  => 'canceled',
            'Shipped'    => 'shipped',
            'Completed' => 'completed',
        ];
    }

    public function gerOrderByDetails(Order $order): array {
        return $this->orderItemRepository->findItemByOrderPlates($order);
    }

}