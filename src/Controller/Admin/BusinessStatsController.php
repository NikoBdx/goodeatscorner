<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;




class BusinessStatsController extends AbstractController
{
    #[Route('/admin_business_stats' , name:'admin_business_stats')]
    public function index(UserRepository $userRepository, OrderRepository $orderRepository,ProductRepository $productRepository)
    {
        $usersStats = $userRepository->usersStats();

        $ordersStats = $orderRepository->ordersStats();

        $products = $productRepository->findAll();

        $stockValue = 0;
        foreach($products as $product) {
            $stockValue  += ($product->getStock() * $product->getPrice());
        }
        $stockProducts = count($products);
        $productsStats = [
            "stockValue" => $stockValue,
            "stockProducts" => $stockProducts
        ];
        return $this->render('admin/business_stats/index.html.twig', [
            'usersStats' => $usersStats[0],
            'ordersStats' =>$ordersStats[0],
            'productsStats' => $productsStats
        ]);
    }


}