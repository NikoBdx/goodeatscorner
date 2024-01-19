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
use App\Form\SearchType;
use App\Model\SearchData;


class ProductController extends AbstractController
{
    private $entityManager;

    private $repository;

    public function __construct(private ManagerRegistry $managerRegistry)
    {
        $this->entityManager = $this->managerRegistry->getManager();
        $this->repository = $this->managerRegistry->getRepository(Product::class);
    }

    #[Route('/product', name: 'app_product', methods: ['GET'])]
    public function index(
        SessionInterface $session,
        ProductRepository $productRepository,
        Request $request,
    ): Response

    {
        $routeName = $request->attributes->get('_route');

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

        $searchData = new SearchData();
        $form = $this->createForm(SearchType::class, $searchData);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $products = $productRepository->findProductsByNameSearch($searchData);

            return $this->render('product/index.html.twig', [
                'form' => $form->createView(),
                'products' => $products,
                'cartProducts' => $cartProducts,
                'routeName' => $routeName,
                'routeParameters' => "cart",
                'title' => "Résultat de votre recherche",
            ]);
        }

        return $this->render('product/index.html.twig', [
            'form' => $form->createView(),
            'products' => $this->repository->findAll(),
            'cartProducts' => $cartProducts,
            'routeName' => $routeName,
            'routeParameters' => "cart",
            'title' => "Dernier arrivage en stock",
        ]);
    }
    // #[Route('/', name: 'post.index', methods: ['GET'])]
    // public function index(
    //     PostRepository $postRepository,
    //     Request $request
    // ): Response {
    //     $searchData = new SearchData();
    //     $form = $this->createForm(SearchType::class, $searchData);

    //     $form->handleRequest($request);
    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $searchData->page = $request->query->getInt('page', 1);
    //         $posts = $postRepository->findBySearch($searchData);

    //         return $this->render('pages/post/index.html.twig', [
    //             'form' => $form->createView(),
    //             'posts' => $posts
    //         ]);
    //     }

    //     return $this->render('pages/post/index.html.twig', [
    //         'form' => $form->createView(),
    //         'posts' => $postRepository->findPublished($request->query->getInt('page', 1))
    //     ]);
    // }


    // #[Route('/product', name: 'app_product')]
    // public function index(SessionInterface $session): Response
    // {
    //     $products = $this->repository->findAll();

    //     $cart = $session->get('cart', []);

    //     $cartProducts = [];
    //     $total = 0;

    //     foreach($cart as $id => $quantity) {
    //         $product = $this->repository->find($id);
    //         $cartProducts[] = [
    //             'product' => $product,
    //             'quantity' => $quantity
    //         ];
    //         $total += $product->getPrice() * $quantity;
    //     }

    //     return $this->render('product/index.html.twig', [
    //         'products' => $products,
    //         'cartProducts' => $cartProducts,
    //     ]);
    // }


    #[Route('/product/family/{family}', name: 'app_product_family')]
    public function getProductsByFamily(
        Request $request,
        SessionInterface $session,
        ProductRepository $productRepository,
        FamilyRepository $familyRepository,
        $family
    ): Response

    {
        $routeName = $request->attributes->get('_route');
        $routeParameters = $request->attributes->get('_route_params');

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
