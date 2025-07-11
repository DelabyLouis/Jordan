<?php

namespace App\Controller\Admin;

use App\Entity\CartItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;

class CartItemCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CartItem::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Article Panier')
            ->setEntityLabelInPlural('Articles Panier')
            ->setPageTitle('index', 'Articles dans les Paniers')
            ->setDefaultSort(['id' => 'DESC'])
            ->setPaginatorPageSize(30);
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
            
            AssociationField::new('cart', 'Panier')
                ->setHelp('Panier auquel appartient cet article'),
            
            AssociationField::new('product', 'Produit')
                ->setHelp('Produit ajouté au panier'),
            
            IntegerField::new('quantity', 'Quantité')
                ->setHelp('Quantité de ce produit dans le panier'),
            
            MoneyField::new('totalPrice', 'Sous-total')
                ->setCurrency('EUR')
                ->setHelp('Prix total pour cette ligne (prix × quantité)')
                ->onlyOnDetail(),
        ];
    }
}
