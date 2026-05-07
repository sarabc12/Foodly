<?php

namespace App\Service;

use App\Entity\Order;

class OrderService {

    
    public function getAvaiableStatuses(): array {
        return  [
            'Pending'  => 'pending',
            'Canceled'  => 'canceled',
            'Shipped'    => 'shipped',
            'Completed' => 'completed',
        ];
    }


}