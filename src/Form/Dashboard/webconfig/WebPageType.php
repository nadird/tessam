<?php

namespace App\Form\Dashboard\webconfig;

use App\Entity\WebPage;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WebPageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',null,[
	            'label' => 'app.form.webpage.name',
			])
	        ->add('slug')
            ->add('title',null,[
	            'label' => 'app.form.webpage.title'
            ])
            ->add('content',CKEditorType::class,[
				'config_name' => 'main_config',
	            'label' => 'app.form.webpage.content'
            ])
            
            ->add('thumbnail',FileType::class,[
	            'mapped' => false,
	            'required' => false,
	            'label' => 'app.form.webpage.thumbnail'
			])
            ->add('heroImage',FileType::class,[
	            'mapped' => false,
	            'required' => false,
	            'label' => 'app.form.webpage.heroImage'
            ])
            ->add('seoDescription',null,[
	            'label' => 'app.form.webpage.seoDescription'
			])
            ->add('author',null,[
	            'label' => 'app.form.webpage.author'
            ])
	        ->add('isActive',null,[
		        'label' => 'app.form.webpage.is_active'
	        ])
	        ->add('submit',SubmitType::class,[
				'label'=>'app.form.webpage.submit',
		        'attr'=>[
					'class'=>'btn btn-primary'
		        ]
	        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => WebPage::class,
	        'translation_domain' => 'forms'
        ]);
    }
}
