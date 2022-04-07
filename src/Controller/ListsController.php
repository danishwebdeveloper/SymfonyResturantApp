<?php

namespace App\Controller;

use App\Entity\Lists;
use App\Form\ListsType;
use App\Repository\ListsRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/lists")
 */
class ListsController extends AbstractController
{
    /**
     * @Route("/", name="app_lists_index", methods={"GET"})
     */
    public function index(ListsRepository $listsRepository): Response
    {
        return $this->render('lists/index.html.twig', [
            'lists' => $listsRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_lists_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ListsRepository $listsRepository): Response
    {
        $list = new Lists();
        $form = $this->createForm(ListsType::class, $list);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $listsRepository->add($list);
            return $this->redirectToRoute('app_lists_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('lists/new.html.twig', [
            'list' => $list,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_lists_show", methods={"GET"})
     */
    public function show(Lists $list): Response
    {
        return $this->render('lists/show.html.twig', [
            'list' => $list,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_lists_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Lists $list, ListsRepository $listsRepository): Response
    {
        $form = $this->createForm(ListsType::class, $list);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $listsRepository->add($list);
            return $this->redirectToRoute('app_lists_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('lists/edit.html.twig', [
            'list' => $list,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_lists_delete", methods={"POST"})
     */
    public function delete($id, ListsRepository $listsRepository, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $lists = $listsRepository->find($id);
        
        $entityManager->remove($lists);
        $entityManager->flush();
        return $this->redirect($this->generateUrl('app_menu'));
    }
}
