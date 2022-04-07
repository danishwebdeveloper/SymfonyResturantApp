<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Dishes;
use App\Repository\DishesRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        // redirect to some CRUD controller
        $routeBuilder = $this->get(AdminUrlGenerator::class);

        return $this->redirect($routeBuilder->setController(DishesCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Admin');
            // ->setFaviconPath('favicon.svg');
    }

    
    public function configureMenuItems(): iterable
    {
        // Here we can give more options to the admin dashboard
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::section('Menu Card');
        yield MenuItem::linkToCrud('Dihses', 'fa fa-diamond', Dishes::class);
        yield MenuItem::linkToCrud('Categories', 'fa fa-diamond', Category::class);
        yield MenuItem::linkToLogout('Logout', 'fa fa-sign-out');
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }
}
