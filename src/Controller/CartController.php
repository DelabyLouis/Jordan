<?php

namespace App\Controller;

use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CartController extends AbstractController
{
    public function __construct(private CartService $cartService)
    {
    }

    #[Route('/cart', name: 'app_cart')]
    public function index(): Response
    {
        $cart = $this->cartService->getCart();

        return $this->render('cart/index.html.twig', [
            'cart' => $cart,
        ]);
    }

    #[Route('/cart/add/{productId}', name: 'app_cart_add', methods: ['POST'])]
    public function add(int $productId, Request $request): JsonResponse
    {
        $quantity = (int) $request->request->get('quantity', 1);
        $result = $this->cartService->addToCart($productId, $quantity);

        return $this->json($result);
    }

    #[Route('/cart/remove/{cartItemId}', name: 'app_cart_remove', methods: ['POST'])]
    public function remove(int $cartItemId): JsonResponse
    {
        $result = $this->cartService->removeFromCart($cartItemId);

        return $this->json($result);
    }

    #[Route('/cart/update/{cartItemId}', name: 'app_cart_update', methods: ['POST'])]
    public function update(int $cartItemId, Request $request): JsonResponse
    {
        $quantity = (int) $request->request->get('quantity');
        
        if ($quantity <= 0) {
            $result = $this->cartService->removeFromCart($cartItemId);
        } else {
            $result = $this->cartService->updateQuantity($cartItemId, $quantity);
        }

        return $this->json($result);
    }

    #[Route('/cart/clear', name: 'app_cart_clear', methods: ['POST'])]
    public function clear(): JsonResponse
    {
        $this->cartService->clearCart();

        return $this->json(['success' => true, 'message' => 'Panier vidé']);
    }

    #[Route('/cart/count', name: 'app_cart_count')]
    public function count(): JsonResponse
    {
        $cart = $this->cartService->getCart();

        return $this->json(['totalItems' => $cart->getTotalItems()]);
    }
}
