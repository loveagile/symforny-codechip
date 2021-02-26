<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdressFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('address', null, ['label' => 'Endereço'])
            ->add('number', null, ['label' => 'Número'])
            ->add('neighborhood', null, ['label' => 'Bairro'])
            ->add('city', null, ['label' => 'Cidade'])
            ->add('state', null, ['label' => 'Estado'])
            ->add('zipcode', null, ['label' => 'CEP'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
