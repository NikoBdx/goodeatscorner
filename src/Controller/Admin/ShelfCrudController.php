<?php

namespace App\Controller\Admin;

use App\Entity\Shelf;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;


class ShelfCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Shelf::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            DateTimeField::new('createdAt')->setFormat('dd/MM/Y hh:mm')->hideonForm(),
            DateTimeField::new('updatedAt')->setFormat('dd/MM/Y hh:mm')->hideonForm(),
        ];
    }
}
