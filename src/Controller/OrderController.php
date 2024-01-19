<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Order;
use App\Entity\OrderDetail;
use App\Repository\OrderDetailRepository;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;




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
        $cart = $session->get('cart', []);
        $products = [];
        $total = 0;

        foreach($cart as $id => $quantity) {
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
        $cart = $session->get('cart', []);
        $products = [];
        $total = 0;

        $user = $this->getUser();

        foreach($cart as $id => $quantity) {
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

            //on vide le panier
            $session->remove('cart');

        }

        return $this->render('layout.html.twig');
    }

    #[Route('/user', name: 'app_order_user')]
    public function showOrdersByUser(OrderRepository $orderRepository): Response
    {
        $user = $this->getUser();
        $orders = $orderRepository->findOrdersByUser($user);

        // $results = [];
        // foreach ( $orders as $order) {
        //     $results[] = [
        //         "order" => $order,
        //         "details" => $orderDetailRepository->findDetailsByOrder($order)
        //     ];
        // }

        // dd($orders);

        return $this->render('order/user_orders.html.twig', [
            "orders" => $orders
        ]);
    }

    #[Route('/user/details/{order}/{number}', name: 'app_order_user_detail')]
    public function showOrderDetails(OrderDetailRepository $orderDetailRepository, ProductRepository $productsRepository,$order, $number): Response
    {

        $details = $orderDetailRepository->findDetailsByOrder($order);

        return $this->render('order/user_order_details.html.twig', [
            "details" => $details,
            "number" => $number
        ]);
    }
}