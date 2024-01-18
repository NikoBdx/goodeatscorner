<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Order;
use App\Entity\OrderDetail;

#[Route('/order')]
class OrderController extends AbstractController
{

    private $entityManager;

    public function __construct(private ManagerRegistry $managerRegistry)
    {
        $this->entityManager = $this->managerRegistry->getManager();
    }

    #[Route('/delivery', name: 'app_order_delivery')]
    public function delivery(SessionInterface $session, ProductRepository $productsRepository): Response
    {
        $panier = $session->get('panier', []);
        $products = [];
        $total = 0;

        foreach($panier as $id => $quantity) {
            $product = $productsRepository->find($id);
            $products[] = [
                'product' => $product,
                'quantity' => $quantity
            ];
            $total += $product->getPrice() * $quantity;
        }

        return $this->render('order/delivery.html.twig', [
            'products' => $products,
            'total' => $total,
        ]);
    }

    #[Route('/confirm', name: 'app_order_confirm')]
    public function confirm(SessionInterface $session, ProductRepository $productsRepository, Request $request): Response
    {
        $panier = $session->get('panier', []);
        $products = [];
        $total = 0;

        $user = $this->getUser();

        foreach($panier as $id => $quantity) {
            $product = $productsRepository->find($id);
            $products[] = [
                'product' => $product,
                'quantity' => $quantity
            ];
            $total += $product->getPrice() * $quantity;
        }

        if ($request->isMethod('POST')) {
            $delivery = $request->request->get('delivery');

            // création de la commande
            $order = new Order();
            $order->setDelivery($delivery);
            $order->setTotal($total);
            $order->setStatus("done");
            $order->setNumber("CMD".date("Ymd").uniqid());
            $order->setUser($user);

            // création des détails de la commande
            foreach($products as $productArray) {
                $orderdetail = new OrderDetail();
                $orderdetail->setOrderRelated($order);
                $orderdetail->setProduct($productArray["product"]);
                $orderdetail->setQuantity($productArray["quantity"]);
                $orderdetail->setUnitPrice($productArray["product"]->getPrice());

                $this->entityManager->persist($orderdetail);
            }
            $this->entityManager->persist($order);
            $this->entityManager->flush();
        }

        return $this->render('home/index.html.twig');
    }

    #[Route('/user', name: 'app_order_user')]
    public function showOrdersByUser(SessionInterface $session, ProductRepository $productsRepository): Response
    {
        $panier = $session->get('panier', []);
        $products = [];
        $total = 0;

        foreach($panier as $id => $quantity) {
            $product = $productsRepository->find($id);
            $products[] = [
                'product' => $product,
                'quantity' => $quantity
            ];
            $total += $product->getPrice() * $quantity;
        }

        return $this->render('order/delivery.html.twig', [
            'products' => $products,
            'total' => $total,
        ]);
    }
}