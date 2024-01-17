<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ManagerRegistry;

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
    public function index(): Response
    {
        $products = $this->repository->findAll();

        return $this->render('product/index.html.twig', [
            'products' => $products,
        ]);
    }
}
