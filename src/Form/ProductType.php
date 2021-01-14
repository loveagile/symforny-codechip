<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, [
                'label'=>'Nome'
            ])
            ->add('description', null, [
                'label'=>'Descrição'
            ])
            ->add('body', null, [
                'label'=>'Conteúdo'
            ])
            ->add('price', null, [
                'label'=>'Preço'
            ])
            ->add('slug')
            ->add('category', null, [
                'label'=>'Categorias',
                'choice_label' => function($category) {
                    return sprintf('(%d) %s', $category->getId(), $category->getName());
                }//,
//                'placeholder' =>'Selecione uma categoria',
//                'multiple' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
