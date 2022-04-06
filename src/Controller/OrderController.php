<?php

namespace App\Controller;

use App\Entity\Dishes;
use App\Entity\Order;
use App\Repository\OrderRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    /**
     * @Route("/order", name="app_order")
     */
    public function index(OrderRepository $orderRepository): Response
    {
        $orderrepo = $orderRepository->findBy([
            'ordertable' => 'table1',
        ]);
        return $this->render('order/index.html.twig', [
            'orders' => $orderrepo,
        ]);
    }

    /**
     * Undocumented function
     *
     * @Route("/order/{id}", name="order")
     */
    public function order(Dishes $dishes, ManagerRegistry $doctrine)
    {
            // die("here");
            // Here get all data and store
            $order = new Order();
            $order->setOrdertable("table1");
            $order->setOrdername($dishes->getName());
            $order->setOrnernumber($dishes->getId());
            $order->setOrderprice($dishes->getPrice());
            $order->setOrderstatus("open");

            // save to db using entity manager
            $entiyManager = $doctrine->getManager();
            $entiyManager->persist($order);
            $entiyManager->flush();

            // return response with flash message
            $this->addFlash('success', $order->getOrdername() . " ordered Successfully!!");
            return $this->redirect($this->generateUrl('app_menu'));
    }

    /**
     * @Route("/status/{id},{status}", name="status")
     */
    public function status($id, $status, ManagerRegistry $doctrine)
    {
        // taking id and status and set the soecific dish id to new status perform by Employee

        $entityManager = $doctrine->getManager();
        $order = $entityManager->getRepository(Order::class)->find($id);
        $order->setOrderstatus($status);
        $entityManager->flush();
        $this->addFlash('success', "Status Updated successfully!!");
        return $this->redirect($this->generateUrl('app_order'));
    }

    /**
     * Undocumented function
     *
     * @param [type] $id
     * @param ManagerRegistry $doctrine
     * @param OrderRepository $orderRepository
     * @Route("/delete/{id}", name="deleteorder")
     */
    public function delete($id, OrderRepository $orderRepository, ManagerRegistry $doctrine)
    {
        $entityManger = $doctrine->getManager();
        $order = $orderRepository->find($id);
        $entityManger->remove($order);
        $entityManger->flush();

        $this->addFlash('success', "Your order deleted successfully!!");
        return $this->redirect($this->generateUrl('app_order'));
    }
}
