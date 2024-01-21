<?php

namespace App\Form;

use App\Entity\Order;
use App\Entity\OrderDetail;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('status')
            ->add('number')
            ->add('delivery')
            ->add('total')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'lastname',
            ])
            ->add('order_related', EntityType::class, [
                'class' => OrderDetail::class,
                'choice_label' => 'designation',
                'multiple' => true,
                'expanded'=> false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}
