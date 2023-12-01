<?php

namespace App\Form\Challenge;

use App\Document\Challenge\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('weight', NumberType::class, array(
                'help' => '(kg)'
            ))
            ->add('length', IntegerType::class, array(
                'help' => '(cm)'
            ))
            ->add('width', IntegerType::class, array(
                'help' => '(cm)'
            ))
            ->add('height', IntegerType::class, array(
                'help' => '(cm)'
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}