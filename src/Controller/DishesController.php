<?php

namespace App\Controller;

use App\Entity\Dishes;
use App\Form\DishesFormType;
use App\Repository\DishesRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

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
    public function create(Request $request, ManagerRegistry $doctrine, SluggerInterface $slugger): Response
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

            // Image Upload
            $imagefile = $form->get('attachment')->getData();
            if($imagefile){
                // $originalFileName = pathinfo($imagefile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL

                // First method to set file name, plus add slug and unique file name
                // $safeFilename = $slugger->slug($originalFileName);
                // $FileNamePlusSlug = $safeFilename.'-'.uniqid().'.'.$imagefile->guessExtension();
                
                // another unique name like md5
                $uniqueFilename =  md5(uniqid()) . '.' . $imagefile->guessExtension();
            }

            // Move the file to the directory where brochures are stored (in config/services.yaml)
            $imagefile->move(
                $this->getParameter('broucher_directory'),
                $uniqueFilename
            );
            
            $dish->setImage($uniqueFilename);
            $entityManager->persist($dish);
            $entityManager->flush();
            $this->addFlash('imagesuccess', 'Image Uploaded Successfully');
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

    /**
     * Undocumented function
     *
     * @Route("/show/{id}", name="show")
     */
    public function show(Dishes $dishes){
        // In parameters, we can also do same we did in remove, but it's another short method called @paramconverter
        // using paramconverter, we can easily access, id, name and all fields in database
        
        // dump($dishes);
        // die();
        return $this->render('dishes/show.html.twig', [
            'showdish' => $dishes,
        ]);
    }

    /**
     * Undocumented function
     *
     * @param [type] $id
     * @Route("/price/{id}", name="price")
     */
    public function price($id, DishesRepository $dishesRepository)
    {
            $dishes = $dishesRepository->findFiveEur($id);
            dd($dishes);
    }
}
