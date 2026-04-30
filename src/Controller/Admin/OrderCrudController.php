<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use App\Service\AdminSecurityService;
use App\Service\OrderService;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\BooleanFilter;

class OrderCrudController extends AbstractCrudController
{

    private OrderService $orderService;
    private AdminSecurityService $adminSecurityService;

    public function __construct(OrderService $orderService, AdminSecurityService $adminSecurityService)
    {
        $this->orderService = $orderService;
        $this->adminSecurityService = $adminSecurityService;
    }

    public static function getEntityFqcn(): string
    {
        return Order::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('code', 'Order Code'),
            DateTimeField::new('date', 'Date & Time')
                ->setFormat('d MMM yyyy hha'),
            ChoiceField::new('status', 'Status')
                ->setChoices($this->orderService->getAvaiableStatuses())
                ->renderAsBadges([
                    'pending' => 'warning',
                    'canceled' => 'danger',
                    'shipped' => 'info',
                    'completed' => 'success',
                ]),
            AssociationField::new('User','Client')
        ];
    }

}
