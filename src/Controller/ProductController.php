<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Repository\ProductRepository;
use App\Repository\ShelfRepository;
use App\Repository\FamilyRepository;
use Symfony\Component\HttpFoundation\Request;



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

        $cart = $session->get('cart', []);

        $cartProducts = [];
        $total = 0;

        foreach($cart as $id => $quantity) {
            $product = $this->repository->find($id);
            $cartProducts[] = [
                'product' => $product,
                'quantity' => $quantity
            ];
            $total += $product->getPrice() * $quantity;
        }

        return $this->render('product/index.html.twig', [
            'products' => $products,
            'cartProducts' => $cartProducts,
        ]);
    }


    #[Route('/product/family/{family}', name: 'app_product_family')]
    public function getProductsByFamily(Request $request, SessionInterface $session, ProductRepository $productRepository, FamilyRepository $familyRepository, $family): Response
    {
        $routeParameters = $request->attributes->get('_route_params');
        $routeName = $request->attributes->get('_route');

        foreach ($routeParameters as $cle => $valeur) {
            $params = "$cle-$valeur";
        }

        $products = $productRepository->findProductsByFamily($family);

        $familyName = $familyRepository->find($family)->getName();

        $cart = $session->get('cart', []);

        $cartProducts = [];
        $total = 0;

        foreach($cart as $id => $quantity) {
            $product = $this->repository->find($id);
            $cartProducts[] = [
                'product' => $product,
                'quantity' => $quantity
            ];
            $total += $product->getPrice() * $quantity;
        }

        return $this->render('product/index.html.twig', [
            'products' => $products,
            'cartProducts' => $cartProducts,
            'title' => $familyName,
            'routeParameters' => $params,
            'routeName' => $routeName
        ]);
    }

    #[Route('/product/shelf/{shelf}', name: 'app_product_shelf')]
    public function getProductsByShelf(Request $request, SessionInterface $session, ProductRepository $productRepository, ShelfRepository $shelfRepository, $shelf): Response
    {
        $routeParameters = $request->attributes->get('_route_params');
        $routeName = $request->attributes->get('_route');

        foreach ($routeParameters as $cle => $valeur) {
            $params = "$cle-$valeur";
        }

        $products = $productRepository->findProductsByShelf($shelf);

        $shelfName = $shelfRepository->find($shelf)->getName();

        $cart = $session->get('cart', []);

        $cartProducts = [];
        $total = 0;

        foreach($cart as $id => $quantity) {
            $product = $this->repository->find($id);
            $cartProducts[] = [
                'product' => $product,
                'quantity' => $quantity
            ];
            $total += $product->getPrice() * $quantity;
        }

        return $this->render('product/index.html.twig', [
            'products' => $products,
            'cartProducts' => $cartProducts,
            'title' => $shelfName,
            'routeParameters' => $params,
            'routeName' => $routeName
        ]);
    }
}
