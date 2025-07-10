<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Vêtements
        $vetements = [
            ['name' => 'T-shirt Blanc Classique', 'price' => 25.99, 'quantity' => 15],
            ['name' => 'Jean Slim Bleu', 'price' => 79.99, 'quantity' => 8],
            ['name' => 'Sweat à Capuche Noir', 'price' => 45.50, 'quantity' => 12],
            ['name' => 'Chemise Blanche', 'price' => 39.99, 'quantity' => 0], // Rupture de stock
            ['name' => 'Pull Laine Gris', 'price' => 65.00, 'quantity' => 5],
            ['name' => 'Veste Cuir Marron', 'price' => 159.99, 'quantity' => 3],
        ];

        foreach ($vetements as $vetementData) {
            $vetement = new Product();
            $vetement->setName($vetementData['name'])
                    ->setPrice($vetementData['price'])
                    ->setType('vêtement')
                    ->setQuantity($vetementData['quantity']);
            
            $manager->persist($vetement);
        }

        // Baskets
        $baskets = [
            ['name' => 'Nike Air Max 90', 'price' => 129.99, 'quantity' => 20],
            ['name' => 'Adidas Stan Smith', 'price' => 89.99, 'quantity' => 15],
            ['name' => 'Converse Chuck Taylor', 'price' => 65.00, 'quantity' => 10],
            ['name' => 'Vans Old Skool', 'price' => 75.50, 'quantity' => 0], // Rupture de stock
            ['name' => 'Puma Suede Classic', 'price' => 79.99, 'quantity' => 7],
            ['name' => 'New Balance 574', 'price' => 95.00, 'quantity' => 12],
            ['name' => 'Jordan 1 Mid', 'price' => 149.99, 'quantity' => 5],
            ['name' => 'Reebok Classic Leather', 'price' => 69.99, 'quantity' => 9],
        ];

        foreach ($baskets as $basketData) {
            $basket = new Product();
            $basket->setName($basketData['name'])
                  ->setPrice($basketData['price'])
                  ->setType('basket')
                  ->setQuantity($basketData['quantity']);
            
            $manager->persist($basket);
        }

        $manager->flush();
    }
}