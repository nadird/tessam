<?php

namespace App\Form\Dashboard\config;

use App\Entity\LeadState;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LeadStateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('code')
            ->add('color',ColorType::class,[
				'attr' => [
					'class' => 'colorpicker'
	            ]
            ])
	        ->add('colorBorder',ColorType::class,[
		        'attr' => [
			        'class' => 'colorpicker'
		        ]
	        ])
	        ->add('colorText',ColorType::class,[
		        'attr' => [
			        'class' => 'colorpicker'
		        ]
	        ])
	        ->add('isLost',ChoiceType::class,[
	        	'choices' => [
	        		'Non' => false,
			        'Oui' => true
		        ]
	        ])
	        ->add('isWon',ChoiceType::class,[
		        'choices' => [
			        'Non' => false,
			        'Oui' => true
		        ]
	        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => LeadState::class,
        ]);
    }
}
