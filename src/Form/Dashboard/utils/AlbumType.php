<?php

namespace App\Form\Dashboard\utils;

use App\Entity\Album;
use App\Entity\CFormatBien;
use App\Entity\CProject;
use App\Form\Dashboard\crm\orders\OrderAdditionnalProducts;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AlbumType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',null,[
				'label' => false,
				'attr'=>[
					'placeholder'=>'app.form.album.name',
				]
			])
            ->add('project',EntityType::class, [
				'class'=>CProject::class,
	            'choice_label' => 'name',
	            'label' => false,
	            'placeholder'=>'app.form.album.project_placeholder',
	            'attr'=>[
					'placeholder'=>'app.form.album.project',
	            ]
            ])
            ->add('format',EntityType::class, [
				'class'=>CFormatBien::class,
	            'choice_label' => 'name',
	            'label' => false,
	            'placeholder'=>'app.form.album.format',
	            'attr'=>[
					'placeholder'=>'app.form.album.format',
	            ]
			])
         
            ->add('additionalProduct',EntityType::class,[
				'class'=>OrderAdditionnalProducts::class,
	            'choice_label' => 'name',
	            'label' => false,
	            'placeholder'=>'app.form.album.additionalProduct',
	            'attr'=>[
					'placeholder'=>'app.form.album.additionalProduct',
	            ]
            ])
	        			->add('submit',SubmitType::class,[
							'label'=>'app.form.album.submit',
					        'attr'=>['class'=>'btn btn-primary']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Album::class,
	        'translation_domain' => 'forms'
        ]);
    }
}
