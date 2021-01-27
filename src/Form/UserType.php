<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName')
            ->add('lastName')
            ->add('email')
            ->add('password', PasswordType::class, [
            	'mapped' => false
            ])
	        ->add('roles', ChoiceType::class, [
	        	'multiple' => true,
	        	'expanded' => true,
	        	'choices' => [
	        		'Administrador' => 'ROLE_ADMIN',
			        'Gerente'       => 'ROLE_MANAGER',
			        'Usuário'       => 'ROLE_USER'
		        ]
	        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
