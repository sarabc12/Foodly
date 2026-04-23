<?php

namespace App\Controller\Admin;

use App\Entity\Restaurant;
use App\Entity\Menu;
use App\Entity\Order;
use App\Entity\Plate;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Foodly');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-dashboard');
        yield MenuItem::subMenu('Restaurant', 'fa fa-store', Restaurant::class)
            ->setSubItems([
                MenuItem::linkToCrud('Menus','fas fa-clipboard-list', Menu::class),
                MenuItem::linkToCrud('Plates', 'fa-solid fa-bowl-food', Plate::class),
            ]);
        yield MenuItem::linkToCrud('Orders', 'fa-solid fa-basket-shopping', Order::class);

    }
}
