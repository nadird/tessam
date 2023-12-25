<?php

namespace App\Form\Dashboard\crm\marketing;

use App\Entity\Compaign;
use App\Entity\CompaignType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompaignFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class,[
				'label'=>'app.form.compaign.name',
	            'attr'=>[
					'class'=>'form-control',
				],
            ])
	        ->add('type',EntityType::class,[
		        'label'=>'app.form.compaign.type',
		        'class'=>CompaignType::class,
		        'choice_label'=>'name',
		        'attr'=>[
			        'class'=>'form-control',
		        ]
	        ])
	        ->add('text',CKEditorType::class,[
				'label'=>'app.form.compaign.text',
	            'attr'=>[
					'class'=>'form-control',
	            ],
	        ])
            ->add('image',FileType::class,[
				'label'=>'app.form.compaign.image',
				'mapped'=>false,
				'required'=>false,
	            'attr'=>[
					'class'=>'form-control',
	            ]
            ])
            ->add('video',FileType::class,[
	            'label'=>'app.form.compaign.video',
	            'mapped'=>false,
	            'required'=>false,
	            'attr'=>[
		            'class'=>'form-control',
	            ]
            ])
            ->add('url',TextType::class,[
				'label'=>'app.form.compaign.url',
	            'attr'=>[
					'class'=>'form-control',
	            ],
            ])
            ->add('isActive',ChoiceType::class,[
				'label'=>'app.form.compaign.isActive',
	            'choices'=>[
	            	'Yes'=>true,
		            'No'=>false,
	            ],
	            'attr'=>[]
            ])
	        ->add('submit',SubmitType::class,[
		        'label'=>'app.form.submit',
		        'attr'=>[
			        'class'=>'btn btn-phoenix-primary',
		        ]
	        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Compaign::class,
	        'translation_domain'=>'forms',
        ]);
    }
}
