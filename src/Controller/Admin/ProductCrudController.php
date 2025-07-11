<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Produit')
            ->setEntityLabelInPlural('Produits')
            ->setPageTitle('index', 'Gestion des Produits')
            ->setPageTitle('new', 'Ajouter un Produit')
            ->setPageTitle('edit', 'Modifier le Produit')
            ->setPageTitle('detail', 'Détails du Produit')
            ->setDefaultSort(['id' => 'DESC'])
            ->setPaginatorPageSize(20);
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
                return $action->setIcon('fa fa-plus')->setLabel('Nouveau Produit');
            })
            ->update(Crud::PAGE_INDEX, Action::EDIT, function (Action $action) {
                return $action->setIcon('fa fa-edit');
            })
            ->update(Crud::PAGE_INDEX, Action::DELETE, function (Action $action) {
                return $action->setIcon('fa fa-trash');
            });
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('name')
            ->add('type')
            ->add('quantity')
            ->add('price');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->onlyOnIndex(),
            
            TextField::new('name', 'Nom du produit')
                ->setRequired(true)
                ->setHelp('Nom du produit tel qu\'il apparaîtra sur le site'),
            
            ChoiceField::new('type', 'Type de produit')
                ->setChoices([
                    'Vêtements' => 'vêtements',
                    'Baskets' => 'baskets',
                ])
                ->setRequired(true)
                ->renderExpanded(false),
            
            MoneyField::new('price', 'Prix')
                ->setCurrency('EUR')
                ->setRequired(true)
                ->setHelp('Prix unitaire en euros'),
            
            IntegerField::new('quantity', 'Stock')
                ->setRequired(true)
                ->setHelp('Quantité disponible en stock')
                ->setFormTypeOption('attr', [
                    'min' => 0,
                    'max' => 9999
                ]),
            
            TextEditorField::new('description', 'Description')
                ->hideOnIndex()
                ->setHelp('Description détaillée du produit (optionnel)'),
            
            DateTimeField::new('createdAt', 'Date de création')
                ->onlyOnDetail()
                ->hideWhenCreating()
                ->hideWhenUpdating(),
        ];
    }
}
