<?php

namespace App\Controller;

use App\Entity\Dishes;
use App\Form\DishesFormType;
use App\Repository\DishesRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\ManagerRegistry as DoctrineManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// Main Router to handle all
/**
 * @Route("/dishes", name="appdishes.")
 */

class DishesController extends AbstractController
{
    /**
     * @Route("/dish", name="dish")
     */
    public function index(DishesRepository $dishesdisplay): Response
    {
        $dishesdisplay = $dishesdisplay->findAll();
        return $this->render('dishes/index.html.twig', [
            'dishes' => $dishesdisplay,
        ]);
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @param ManagerRegistry $doctrine
     * @return Response
     * @Route("/create", name="create")
     */
    public function create(Request $request, ManagerRegistry $doctrine): Response
    {
        // Create Dish
        $dish = new Dishes();
        // $dish->setName('Pizza');
        // $dish->setDescription('Our Best Pizza');

        // instead of above we make form to add data
        $form = $this->createForm(DishesFormType::class, $dish);

        // now store data in DB using form
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()){
            $entityManager = $doctrine->getManager();
            $entityManager->persist($dish);
            $entityManager->flush();
            return $this->redirect($this->generateUrl('appdishes.dish'));
        }

        return $this->render('dishes/create.html.twig', [
            'formview' => $form->createView(),
        ]);
        


        // Store in the database, need entityManager
        // $entityManager = $doctrine->getManager();
        // $entityManager->persist($dish);
        
        
        // Store in DB
        // $entityManager->flush();

        // Response
        // return new response("Dish created Successfully!!");

       
    }

    /**
     * Undocumented function
     *
     * @Route("/delete/{id}", name="delete")
     */
    public function delete($id, DishesRepository $dishesRepository, ManagerRegistry $doctrine)
    {
        $getitem = $dishesRepository->find($id);
        // entity Manager
        $entityManager = $doctrine->getManager();
        $entityManager->remove($getitem);
        $entityManager->flush();

        // Flash Messages in Symfony
        $this->addFlash('success', 'Deleted Successfully');

        return $this->redirect($this->generateUrl('appdishes.dish'));
    }
}
