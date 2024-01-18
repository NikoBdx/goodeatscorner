<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ProductController extends AbstractController
{
    private $entityManager;

    private $repository;

    public function __construct(private ManagerRegistry $managerRegistry)
    {
        $this->entityManager = $this->managerRegistry->getManager();
        $this->repository = $this->managerRegistry->getRepository(Product::class);
    }

    #[Route('/product', name: 'app_product')]
    public function index(SessionInterface $session): Response
    {
        $products = $this->repository->findAll();

        $panier = $session->get('panier', []);

        $cartProducts = [];
        $total = 0;

        foreach($panier as $id => $quantity) {
            $product = $this->repository->find($id);
            $cartProducts[] = [
                'product' => $product,
                'quantity' => $quantity
            ];
            $total += $product->getPrice() * $quantity;
        }

        dump($cartProducts);

        return $this->render('product/index.html.twig', [
            'products' => $products,
            'cartProducts' => $cartProducts
        ]);
    }
}
