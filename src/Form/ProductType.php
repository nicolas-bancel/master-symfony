<?php

namespace App\Form;

use App\Entity\Product;
use App\Form\Type\TagsInputType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('price', MoneyType::class, [
                'divisor' => 100
            ])
            ->add('slug')
            ->add ('category')
            ->add('user')
            ->add('Tags', null, [
                'choice_label' => 'name',
                'expanded' => 'true'
            ])
            ->add('Tags', TagsInputType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
