<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;



#[Route('/cart')]
class CartController extends AbstractController
{
    #[Route('', name: 'app_cart')]
    public function index(Request $request, SessionInterface $session, ProductRepository $productsRepository)
    {
        $routeName = $request->attributes->get('_route');

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

        return $this->render('cart/index.html.twig', [
            'products' => $products,
            'total' => $total,
            'routeName' => $routeName
        ]);
    }

    #[Route('/quantity/{id}', name: 'app_cart_quantity')]
    public function showQauntity(Product $product, SessionInterface $session)
    {
        $quantity = 0;

        $id = $product->getId();

        $cart = $session->get('cart', []);

        if(empty($cart[$id])){
            return $quantity;
        }else{
            return $cart[$id];
        }
    }


    #[Route('/add/{id}/{routeName}/{routeParameters}', name: 'app_cart_add')]
    public function add(Product $product, SessionInterface $session, $routeName, $routeParameters )
    {
        if ($routeParameters === "cart") {
            $params = [];
        } else {
            $elements = explode('-', trim($routeParameters));
            $params = [$elements[0] => $elements[1]];
        }

        $id = $product->getId();

        $stockProduct = $product->getStock();

        $cart = $session->get('cart', []);

        if(empty($cart[$id])){
            $cart[$id] = 1;
        }else{
            if($cart[$id]) {
                //si l'ajout au panier rend le stock négatif (impossible)
                if($stockProduct - $cart[$id] === 0 ) {
                    $this->addFlash('error', 'Pas assez de stock pour ajouter ce produit.');
                    return $this->redirectToRoute($routeName, $params);
                }
                $cart[$id]++;
            }
        }

        $session->set('cart', $cart);

        $this->addFlash('success', 'Le produit a bien été ajouté au panier.');
        return $this->redirectToRoute($routeName, $params);

    }

    #[Route('/remove/{id}/{routeName}/{routeParameters}', name: 'app_cart_remove')]
    public function remove(Product $product, SessionInterface $session, $routeName, $routeParameters)
    {
        $id = $product->getId();

        $cart = $session->get('cart', []);

        if(!empty($cart[$id])){
            if($cart[$id] > 1){
                $cart[$id]--;
            }else{
                unset($cart[$id]);
            }
        }

        $session->set('cart', $cart);

        if ($routeParameters === "cart") {
            $params = [];
        } else {
            $elements = explode('-', trim($routeParameters));
            $params = [$elements[0] => $elements[1]];
        }

        $this->addFlash('success', 'Le produit a bien été retiré du panier.');
        return $this->redirectToRoute($routeName, $params);
    }

    #[Route('/delete/{id}', name: 'app_cart_delete')]
    public function delete(Product $product, SessionInterface $session)
    {
        $id = $product->getId();
        $cart = $session->get('cart', []);

        if(!empty($cart[$id])){
            unset($cart[$id]);
        }

        $session->set('cart', $cart);

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/empty', name: 'app_cart_empty')]
    public function empty(SessionInterface $session)
    {
        $session->remove('cart');

        return $this->redirectToRoute('app_cart');
    }
}
