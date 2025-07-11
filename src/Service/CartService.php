<?php

namespace App\Service;

use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\Product;
use App\Repository\CartRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class CartService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private CartRepository $cartRepository,
        private ProductRepository $productRepository,
        private RequestStack $requestStack
    ) {
    }

    public function getCart(): Cart
    {
        $session = $this->requestStack->getSession();
        $sessionId = $session->getId();

        $cart = $this->cartRepository->findBySessionId($sessionId);

        if (!$cart) {
            $cart = new Cart();
            $cart->setSessionId($sessionId);
            $this->entityManager->persist($cart);
            $this->entityManager->flush();
        }

        return $cart;
    }

    public function addToCart(int $productId, int $quantity = 1): array
    {
        $product = $this->productRepository->find($productId);
        
        if (!$product) {
            return ['success' => false, 'message' => 'Produit non trouvé'];
        }

        if ($product->getQuantity() < $quantity) {
            return ['success' => false, 'message' => 'Stock insuffisant'];
        }

        $cart = $this->getCart();

        // Vérifier si le produit est déjà dans le panier
        $cartItem = null;
        foreach ($cart->getCartItems() as $item) {
            if ($item->getProduct()->getId() === $productId) {
                $cartItem = $item;
                break;
            }
        }

        if ($cartItem) {
            // Vérifier le stock pour la nouvelle quantité
            $newQuantity = $cartItem->getQuantity() + $quantity;
            if ($product->getQuantity() < $newQuantity) {
                return ['success' => false, 'message' => 'Stock insuffisant'];
            }
            $cartItem->setQuantity($newQuantity);
        } else {
            $cartItem = new CartItem();
            $cartItem->setCart($cart);
            $cartItem->setProduct($product);
            $cartItem->setQuantity($quantity);
            $cart->addCartItem($cartItem);
            $this->entityManager->persist($cartItem);
        }

        // Décrémenter le stock
        $product->setQuantity($product->getQuantity() - $quantity);

        $this->entityManager->flush();

        return [
            'success' => true, 
            'message' => 'Produit ajouté au panier',
            'totalItems' => $cart->getTotalItems()
        ];
    }

    public function removeFromCart(int $cartItemId): array
    {
        $cartItem = $this->entityManager->getRepository(CartItem::class)->find($cartItemId);
        
        if (!$cartItem) {
            return ['success' => false, 'message' => 'Article non trouvé'];
        }

        // Remettre le stock
        $product = $cartItem->getProduct();
        $product->setQuantity($product->getQuantity() + $cartItem->getQuantity());

        $cart = $cartItem->getCart();
        $cart->removeCartItem($cartItem);
        $this->entityManager->remove($cartItem);
        $this->entityManager->flush();

        return [
            'success' => true,
            'message' => 'Article supprimé du panier',
            'totalItems' => $cart->getTotalItems()
        ];
    }

    public function updateQuantity(int $cartItemId, int $newQuantity): array
    {
        $cartItem = $this->entityManager->getRepository(CartItem::class)->find($cartItemId);
        
        if (!$cartItem) {
            return ['success' => false, 'message' => 'Article non trouvé'];
        }

        $product = $cartItem->getProduct();
        $oldQuantity = $cartItem->getQuantity();
        $difference = $newQuantity - $oldQuantity;

        // Vérifier le stock disponible
        if ($difference > 0 && $product->getQuantity() < $difference) {
            return ['success' => false, 'message' => 'Stock insuffisant'];
        }

        // Mettre à jour le stock
        $product->setQuantity($product->getQuantity() - $difference);
        $cartItem->setQuantity($newQuantity);

        $this->entityManager->flush();

        return [
            'success' => true,
            'message' => 'Quantité mise à jour',
            'totalItems' => $cartItem->getCart()->getTotalItems()
        ];
    }

    public function clearCart(): void
    {
        $cart = $this->getCart();
        
        // Remettre les stocks
        foreach ($cart->getCartItems() as $cartItem) {
            $product = $cartItem->getProduct();
            $product->setQuantity($product->getQuantity() + $cartItem->getQuantity());
        }

        foreach ($cart->getCartItems() as $cartItem) {
            $this->entityManager->remove($cartItem);
        }

        $this->entityManager->flush();
    }
}
