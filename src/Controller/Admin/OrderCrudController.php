<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;



class OrderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Order::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Commande')
            ->setEntityLabelInPlural('Commandes')
        ;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('upperNumber', 'Numéro'),
            TextField::new('status', 'Statut'),
            BooleanField::new('delivery' , 'Livraison')->setDisabled(),
            MoneyField::new('total', 'Total')->setCurrency('EUR')->setFormTypeOption('divisor', 1),
            AssociationField::new('user', 'Client'),
            ArrayField::new('orderDetails', 'Détails')->onlyOnDetail(),
            DateTimeField::new('createdAt','Créée le')->setFormat('dd/MM/Y hh:mm')->hideonForm(),
            DateTimeField::new('updatedAt','Mis à jour le')->setFormat('dd/MM/Y hh:mm')->hideonForm(),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->update(Crud::PAGE_INDEX, Action::EDIT, function (Action $action) {
                return $action->setLabel('Editer');
            })
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->update(Crud::PAGE_INDEX, Action::DETAIL, function (Action $action) {
                return $action->setLabel('Voir');
            })
            ->disable(Action::DELETE,Action::NEW, Action::EDIT)
            ->update(Crud::PAGE_DETAIL, Action::EDIT, function (Action $action) {
                return $action->setLabel('Sauvegarder');
            })
        ;
    }
}
