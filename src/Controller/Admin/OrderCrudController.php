<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use App\Service\AdminSecurityService;
use App\Service\OrderService;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\BooleanFilter;
use Override;

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

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }


    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('code', 'Order Code');
        yield DateTimeField::new('date', 'Date & Time')
                ->setFormat('d MMM yyyy hha');
        yield  ChoiceField::new('status', 'Status')
                ->setChoices($this->orderService->getAvaiableStatuses())
                ->renderAsBadges([
                    'pending' => 'warning',
                    'canceled' => 'danger',
                    'shipped' => 'info',
                    'completed' => 'success',
                ]);
        yield AssociationField::new('User','Client');
        if(Crud::PAGE_DETAIL === $pageName){
            yield CollectionField::new('orderItems', 'Plates')
                ->setTemplatePath('admin/order/order_items_list_html.twig')
                ->onlyOnDetail();
        }
    }

}
