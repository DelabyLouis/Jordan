<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Entity\User;
use App\Entity\Cart;
use App\Entity\CartItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        // Affichage direct du dashboard au lieu de redirection
        return $this->render('admin/dashboard.html.twig');
    }

    #[Route('/admin/help', name: 'admin_help')]
    public function help(): Response
    {
        return $this->render('admin/help.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Administration - Ma Boutique')
            ->setFaviconPath('favicon.ico')
            ->setLocales(['fr' => 'Français']);
    }

    public function configureAssets(): Assets
    {
        return Assets::new()->addCssFile('admin.css');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        
        yield MenuItem::section('Catalogue');
        yield MenuItem::linkToCrud('Produits', 'fas fa-shopping-bag', Product::class);
        
        yield MenuItem::section('Commandes');
        yield MenuItem::linkToCrud('Paniers', 'fas fa-shopping-cart', Cart::class);
        yield MenuItem::linkToCrud('Articles Panier', 'fas fa-list', CartItem::class);
        
        yield MenuItem::section('Utilisateurs');
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-users', User::class);
        
        yield MenuItem::section('Site');
        yield MenuItem::linkToRoute('Voir le site', 'fas fa-eye', 'app_home');
        yield MenuItem::linkToRoute('Guide d\'utilisation', 'fas fa-question-circle', 'admin_help');
    }
}
