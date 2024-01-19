<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


#[Route('/cart')]
class CartController extends AbstractController
{
    #[Route('/', name: 'app_cart')]
    public function index(SessionInterface $session, ProductRepository $productsRepository)
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

        return $this->render('cart/index.html.twig', [
            'products' => $products,
            'total' => $total,
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


    #[Route('/add/{id}/{from}', name: 'app_cart_add')]
    public function add(Product $product, $from, SessionInterface $session)
    {
        $id = $product->getId();

        $cart = $session->get('cart', []);

        if(empty($cart[$id])){
            $cart[$id] = 1;
        }else{
            $cart[$id]++;
        }

        $session->set('cart', $cart);

        //si la route est appellé depuis la page cart on reste sur cette page
        if ($from === "cart") {
            return $this->redirectToRoute('app_cart');
        }
        //si la route est appellé depuis la page des produits on reste sur cete page
        if ($from === "products") {
            return $this->redirectToRoute('app_product');
        }
    }

    #[Route('/remove/{id}/{from}', name: 'app_cart_remove')]
    public function remove(Product $product, $from, SessionInterface $session)
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

        //si la route est appellé depuis la page cart on reste sur cette page
        if ($from === "cart") {
            return $this->redirectToRoute('app_cart');
        }
        //si la route est appellé depuis la page des produits on reste sur cete page
        if ($from === "products") {
            return $this->redirectToRoute('app_product');
        }
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
