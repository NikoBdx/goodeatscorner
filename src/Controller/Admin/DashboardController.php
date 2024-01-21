<?php

namespace App\Controller\Admin;

use App\Entity\Family;
use App\Entity\Order;
use App\Entity\OrderDetail;
use App\Entity\Product;
use App\Entity\Shelf;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig');

    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Goodeatscorner - Administration');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Tableau de bord', 'fa fa-home');
        yield MenuItem::linkToCrud('Produits', 'fa fa-shopping-cart', Product::class);
        yield MenuItem::linkToCrud('Rayons', 'fa fa-archive', Shelf::class);
        yield MenuItem::linkToCrud('Familles', 'fa fa-archive', Family::class);
        yield MenuItem::linkToCrud('Commandes', 'fa fa-file-text-o', Order::class);
        yield MenuItem::linkToCrud('Détails des commandes', 'fa fa-file-text-o', OrderDetail::class);

        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);

    }
}
