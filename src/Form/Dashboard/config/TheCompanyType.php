<?php

namespace App\Form\Dashboard\config;

use App\Entity\TheCompany;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TheCompanyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class,
	            [
					'label'=>'app.form.the_company_config.name'
	            ]
            )
            ->add('shortDescription',CKEditorType::class,
	            [
					'label'=>'app.form.the_company_config.short_description',
		            'attr'=>[
			            'rows'=>3
		            ]
	            ]
            )
            ->add('longDescription',CKEditorType::class,
	            [
					'label'=>'app.form.the_company_config.long_description',
		            'attr'=>[
			            'rows'=>5
		            ]
	            ]
					)
	        ->add('indexTitle',TextType::class,
	            [
					'label'=>'app.form.the_company_config.index_title',
		            'attr'=>[
			            'rows'=>5
		            ]
	            ]
					)
	        ->add('indexSubtitle',TextType::class,
	            [
					'label'=>'app.form.the_company_config.index_subtitle',
		            'attr'=>[
			            'rows'=>5
		            ]
	            ]
					)
	        ->add('raisonSocial',TextType::class,
	            [
					'label'=>'app.form.the_company_config.raison_sociale',
		            'attr'=>[
			            'rows'=>5
		            ]
	            ]
					)->add('address',TextType::class,
	            [
					'label'=>'app.form.the_company_config.address',
		            'attr'=>[
			            'rows'=>5
		            ]
	            ]
					)->add('localisation',TextType::class,
	            [
					'label'=>'app.form.the_company_config.localisation',
		            'attr'=>[
			            'rows'=>5
		            ]
	            ]
					)
	        ->add('diplayPrices',ChoiceType::class,[
				'label'=>'app.form.the_company_config.display_prices',
				'expanded'=>false,
				'multiple'=>false,
				'required'=>true,
				//'placeholder'=>'app.the_company_config.display_prices_placeholder',
		        'choices'=>[
		        	'app.form.the_company_config.display_prices_true'=>true,
			        'app.form.the_company_config.display_prices_false'=>false
		        ],
		        'attr'=>[
			        'class'=>'custom-control-input'
		        ]
		        
				
	        ])
            ->add('logoMainLight',FileType::class, [
					'label'=>'app.form.the_company_config.logo_main_light',
		            'mapped'=>false,
		            'required'=>false
	            ])
	        ->add('logoPrint',FileType::class, [
					'label'=>'app.form.the_company_config.logo_print',
		            'mapped'=>false,
		            'required'=>false
	            ])
            ->add('logoMainDark',FileType::class,
	            [
		            'label'=>'app.form.the_company_config.logo_main_dark',
		            'mapped'=>false,
		            'required'=>false
	            ])
            ->add('logoMobileLight',FileType::class,
	            [
		            'label'=>'app.form.the_company_config.logo_mobile_light',
		            'mapped'=>false,
		            'required'=>false
	            ])
            ->add('logoMobileDark',FileType::class,
	            [
		            'label'=>'app.form.the_company_config.logo_mobile_dark',
		            'mapped'=>false,
		            'required'=>false
	            ])
            ->add('favicon',FileType::class,
	            [
		            'label'=>'app.form.the_company_config.favicon',
		            'mapped'=>false,
		            'required'=>false
	            ])
            ->add('ogImage',FileType::class,
	            [
		            'label'=>'app.form.the_company_config.og_image',
		            'mapped'=>false,
		            'required'=>false
	            ])
            ->add('imageHome',FileType::class,
	            [
		            'label'=>'app.form.the_company_config.image_home',
		            'mapped'=>false,
		            'required'=>false
	            ])
            ->add('videoHome',FileType::class,
	            [
		            'label'=>'app.form.the_company_config.video_home',
		            'mapped'=>false,
		            'required'=>false
	            ])
            ->add('secondText',CKEditorType::class,
	            [
		            'label'=>'app.form.the_company_config.second_text',
		            'attr'=>[
			            'rows'=>5
		            ]
	            ])
            ->add('secondImage',FileType::class,
	            [
		            'label'=>'app.form.the_company_config.second_image',
		            'mapped'=>false,
		            'required'=>false
	            ])
            ->add('facebookLink',TextType::class,
	            [
		            'label'=>'app.form.the_company_config.facebook_link',
	            ])
            ->add('instagram',TextType::class,
	            [
		            'label'=>'app.form.the_company_config.instagram',
	            ])
            ->add('twitter',TextType::class,
	            [
		            'label'=>'app.form.the_company_config.twitter',
	            ])
            ->add('tiktok',TextType::class,
	            [
		            'label'=>'app.form.the_company_config.tiktok',
	            ])
            ->add('linkedin',TextType::class,
	            [
		            'label'=>'app.form.the_company_config.linkedin',
	            ])
            ->add('messenger',TextType::class,
	            [
		            'label'=>'app.form.the_company_config.messenger',
	            ])
            ->add('whatsapp',TextType::class,
	            [
		            'label'=>'app.form.the_company_config.whatsapp',
	            ])
            ->add('viber',TextType::class,
	            [
		            'label'=>'app.form.the_company_config.viber',
	            ])
            ->add('email1',TextType::class,
	            [
		            'label'=>'app.form.the_company_config.email1',
	            ])
            ->add('email2', TextType::class,
	            [
		            'label'=>'app.form.the_company_config.email2',
	            ])
            ->add('phone1',TextType::class,
	            [
		            'label'=>'app.form.the_company_config.phone1',
	            ])
            ->add('phone2',TextType::class,
	            [
		            'label'=>'app.form.the_company_config.phone2',
	            ])
	        ->add('facebookPixel',TextType::class,
	            [
		            'label'=>'app.form.the_company_config.facebook_pixel',
	            ])
	        ->add('analytics',TextType::class,
	            [
		            'label'=>'app.form.the_company_config.google_analytics',
	            ])
	        ->add('googleTagManager',TextType::class, [
		            'label'=>'app.form.the_company_config.google_tag_manager',
	            ])
	        ->add('agrement',TextType::class, [
		            'label'=>'app.form.the_company_config.agrement',
	            ])
	        ->add('agrementDate',DateTimeType::class, [
		            'label'=>'app.form.the_company_config.agrement_date',
		            'widget'=>'single_text',
		            
	            ])
	        ->add('ntnpi',TextType::class, [
		            'label'=>'app.form.the_company_config.ntnpi',
	            ])
	        ->add('ntnpiDate',DateTimeType::class, [
		        'label'=>'app.form.the_company_config.ntnpi_date',
		        'widget'=>'single_text',
	        
	        ])
	        ->add('nfgcmpi',TextType::class, [
		        'label'=>'app.form.the_company_config.nfgcmpi',
	        ])
	        ->add('legalRep',EntityType::class, [
		        'label'=>'app.form.the_company_config.legal_representative',
		        'class'=>'App\Entity\Employee',
	        
	        ])
	        ->add('submit',SubmitType::class,
		        [
			        'label'=>'app.form.the_company_config.submit',
			        'attr'=>[
				        'class'=>'btn btn-primary'
			        ]
		        ]
	        )
	        
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TheCompany::class,
	        'translation_domain'=>'forms'
        ]);
    }
}
