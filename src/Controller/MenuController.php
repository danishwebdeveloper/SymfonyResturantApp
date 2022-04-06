<?php

namespace App\Controller;

use App\Entity\Dishes;
use App\Repository\DishesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MenuController extends AbstractController
{
    /**
     * @Route("/menu", name="app_menu")
     */
    public function menu(DishesRepository $dishesRepository): Response
    {
        // Get all dishes and pass it to the twig
        $alldishes = $dishesRepository->findAll();

        return $this->render('menu/index.html.twig', [
            'alldishes' => $alldishes,
        ]);
    }
}
