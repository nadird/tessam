<?php

namespace App\Form\Dashboard\webconfig;

use App\Entity\BlogCategory;
use App\Entity\BlogPost;
use App\Entity\Employee;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BlogPostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('publicTitle',TextType::class,[
				'label' => 'app.form.blogpost.publicTitle',
            ])
            ->add('seoTitle',TextType::class,[
				'label' => 'app.form.blogpost.seoTitle'
            ])
	        ->add('slug',TextType::class,['label' => 'app.form.blogpost.slug'])
            ->add('body',CKEditorType::class,[
				'config_name' => 'main_config',
				'label' => 'app.form.blogpost.body'
            ])
            ->add('thumbnail',FileType::class,[
				'label' => 'app.form.blogpost.thumbnail',
	            'mapped' => false,
	            'required' => false,
            ])
            ->add('image',FileType::class,[
				'label' => 'app.form.blogpost.image',
	            'mapped' => false,
	            'required' => false,
            ])
            ->add('seoDescription',null,['label' => 'app.form.blogpost.seoDescription'])
            ->add('imgOg',FileType::class,[
				'mapped' => false,
				'required' => false,
				'label' => 'app.form.blogpost.imgOg'
            ])
            ->add('isActive',ChoiceType::class,[
	            'label' => 'app.form.blogpost.isActive_label',
	            'choices' => [
		            'app.form.blogpost.isActive_no' => false,
		            'app.form.blogpost.isActive_yes' => true,
	            ],
	            'data' => false,
	            'multiple' => false,
	            'expanded' => false,
	            'required' => false,
            
            ])
	        ->add('isFeatured',ChoiceType::class,[
				'label' => 'app.form.blogpost.isFeatured_label',
		        'choices' => [
					'app.form.blogpost.isFeatured_no' => false,
			        'app.form.blogpost.isFeatured_yes' => true,
                ],
				'data' => false,
		        'multiple' => false,
		        'expanded' => false,
		        'required' => false,
		        
	        ])
            ->add('author',EntityType::class,[
				'class' => Employee::class,
				'label' => 'app.form.blogpost.author',
	            'placeholder'=>'app.form.blogpost.author_placeholder'
            
            ])
            ->add('categories',EntityType::class, [
					'class' => BlogCategory::class,
					'multiple' => true,
		            'required' => false,
		            'label' => 'app.form.blogpost.category',
		            'placeholder'=>'app.form.blogpost.category_placeholder'
	            ]
			)
	        ->add('relatedPosts',EntityType::class,[
				'class' => BlogPost::class,
		        'multiple' => true,
				'required' => false,
		        'label' => 'app.form.blogpost.relatedPosts',
		        
	        ])
	        ->add('submit',SubmitType::class,[
				'label'=>'app.form.blogpost.submit',
		        'attr'=>[
					'class'=>'btn btn-primary'
		        ]
	        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BlogPost::class,
	        'translation_domain' => 'forms'
        ]);
    }
}
