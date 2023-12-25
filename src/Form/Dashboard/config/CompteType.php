<?php

namespace App\Form\Dashboard\config;

use App\Entity\Compte;
use App\Entity\PaiementType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('code')
            ->add('color')
            ->add('isActive')
	        ->add('rib')
	        ->add('paimentTypes',EntityType::class,[
				'class'=>PaiementType::class,
	        	'choice_label'=>'name',
		        'multiple'=>true,
		        'expanded'=>true,
	        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Compte::class,
        ]);
    }
}
