<?php

namespace App\Form\Dashboard\utils;

use App\Entity\Document;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DocumentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
	        ->add('name',null,[
				'label'=>false,
		        'attr'=>[
					'placeholder'=>'app.form.document.name',
					'class'=>'form-control'
		        ]
		        ])
            ->add('url',FileType::class,[
	            'label'=>'app.form.document.url',
	            'mapped'=>false,
	            'required'=>false,
	            'attr'=>[
		            'class'=>'form-control'
	            ]
			])
            ->add('type',EntityType::class,[
	            'class'=>\App\Entity\DocumentType::class,
	            'choice_label'=>'name',
	            'label'=>'app.form.document.type',
	            'attr'=>[
		            'class'=>'form-control'
	            ]
			])
	        ->add('submit',SubmitType::class,[
		        'label'=>'app.form.document.submit',
		        'attr'=>[
			        'class'=>'btn btn-primary'
		        ]]
	        )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Document::class,
	        'csrf_protection'=>false,
	        'translation_domain' => 'forms'
        ]);
    }
}
