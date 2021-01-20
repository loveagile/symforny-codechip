<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
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
            ->add('price', TextType::class, [
                'label'=>'Preço'
            ])
            ->add('photos', FileType::class, [
                'mapped' => false,
                'multiple' => true
            ])
            ->add('category', null, [
                'label'=>'Categorias',
                'choice_label' => function($category) {
                    return sprintf('(%d) %s', $category->getId(), $category->getName());
                }//,
//                'placeholder' =>'Selecione uma categoria',
//                'multiple' => false
            ])
        ;

        $builder->get('price')
            ->addModelTransformer(new CallbackTransformer(
                function ($price){
                    //Para a exibição do dado no input
                    $price = $price / 100;
                    return number_format($price, 2,',','.');
                },
                    function ($price){
                        //para retorno ao controller
                        $price = str_replace(['.',','], ['', '.'], $price);
                        $price = $price * 100;
                        $price = (int) ceil($price);
                        return $price;
                    })
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
