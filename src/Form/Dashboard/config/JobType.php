<?php

namespace App\Form\Dashboard\config;

use App\Entity\EmplJobs;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JobType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
	        ->add('roles')
	        ->add('submit',SubmitType::class,[
		        'label' => 'Save',
		        'attr' => [
			        'class' => 'btn btn-primary'
		        ]
	        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EmplJobs::class,
        ]);
    }
}
