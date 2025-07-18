<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Request $request, ProductRepository $productRepository, CartService $cartService): Response
    {
        $type = $request->query->get('type');
        
        // Récupérer tous les produits ou filtrer par type
        if ($type) {
            $products = $productRepository->findBy(['type' => $type]);
        } else {
            $products = $productRepository->findAll();
        }

        $cart = $cartService->getCart();

        return $this->render('home/index.html.twig', [
            'products' => $products,
            'current_type' => $type,
            'cartItemCount' => $cart->getTotalItems(),
        ]);
    }
}
