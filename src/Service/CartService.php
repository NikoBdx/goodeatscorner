<?php
namespace App\Service;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService
{
    public function getCart(SessionInterface $session, ProductRepository $productsRepository)
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

        return [
            'products' => $products,
            'total' => $total,
        ];
    }

    public function addOneProduct(Product $product, $from, SessionInterface $session, $id)
    {
        $id = $product->getId();

        $cart = $session->get('cart', []);

        if(empty($cart[$id])){
            $cart[$id] = 1;
        }else{
            $cart[$id]++;
        }

        $session->set('cart', $cart);
    }

    public function removeOneProduct(Product $product, $from, SessionInterface $session, $id)
    {
        $id = $product->getId();

        $cart = $session->get('cart', []);

        if(empty($cart[$id])){
            $cart[$id] = 1;
        }else{
            $cart[$id]++;
        }

        $session->set('cart', $cart);
    }

    public function getQuantityProduct(Product $product, SessionInterface $session, $id)
    {
        $quantity = 0;

        $product->getId($id);

        $cart = $session->get('cart', []);

        if(empty($cart[$id])){
            return $quantity;
        }else{
            return $cart[$id];
        }
    }

}
