<?php

namespace App\Form\Dashboard\config;

use App\Entity\ContactState;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactStateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('code')
            ->add('color',TextType::class,[
				
				'label' => 'Couleur',
	            'attr' => [
	                'class' => 'colorpicker',
		            
				]
            ])
	        ->add('colorText',TextType::class,[
	            
	            'label' => 'Text'
            ])
			->add('colorBorder',TextType::class,[
				
				'label' => 'Bordure'
			])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ContactState::class,
        ]);
    }
}
