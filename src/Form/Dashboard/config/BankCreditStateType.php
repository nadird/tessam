<?php

namespace App\Form\Dashboard\config;

use App\Entity\BankCreditState;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BankCreditStateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('code')
            ->add('color',ColorType::class,[
	            'attr'=>[
		            'class'=>'jscolor'
	            ]
			])
            ->add('isValid',null,[
				'required'=>false,
	            'attr'=>[
		            'class'=>'custom-control-input'
	            ]
			])
	        ->add('submit',SubmitType::class,[
		        'attr'=>[
			        'class'=>'btn btn-primary btn-block'
		        ]
	        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BankCreditState::class,
	        'translation_domain' => 'forms',
	        
        ]);
    }
}
