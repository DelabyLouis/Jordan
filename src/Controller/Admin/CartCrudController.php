<?php

namespace App\Controller\Admin;

use App\Entity\Cart;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;

class CartCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Cart::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Panier')
            ->setEntityLabelInPlural('Paniers')
            ->setPageTitle('index', 'Gestion des Paniers')
            ->setDefaultSort(['createdAt' => 'DESC'])
            ->setPaginatorPageSize(20);
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->disable(Action::NEW)
            ->disable(Action::EDIT);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            
            TextField::new('sessionId', 'ID Session')
                ->setHelp('Identifiant de session du visiteur'),
            
            DateTimeField::new('createdAt', 'Date de création'),
            
            IntegerField::new('totalItems', 'Nb Articles')
                ->setHelp('Nombre total d\'articles dans le panier'),
            
            MoneyField::new('totalPrice', 'Total')
                ->setCurrency('EUR')
                ->setHelp('Montant total du panier'),
            
            CollectionField::new('cartItems', 'Articles')
                ->onlyOnDetail()
                ->setHelp('Liste des articles dans ce panier'),
        ];
    }
}
