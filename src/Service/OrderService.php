<?php

namespace App\Service;

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