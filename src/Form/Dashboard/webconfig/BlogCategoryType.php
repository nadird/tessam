<?php

namespace App\Form\Dashboard\webconfig;

use App\Entity\BlogCategory;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BlogCategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',null,[
				'label' => 'app.form.blogcategory.name',
            ])
	        ->add('slug',null,['label' => 'app.form.blogcategory.slug'])
            ->add('description',CKEditorType::class,[
				'config_name' => 'main_config',
				'label' => 'app.form.blogcategory.description'
            ])
            
            ->add('thumbnail',FileType::class,[
				'label' => 'app.form.blogcategory.thumbnail',
	            'mapped' => false,
	            'required' => false,
            ])
            ->add('image',FileType::class,[
				'label' => 'app.form.blogcategory.image',
	            'mapped' => false,
	            'required' => false,
	            
            ])
            ->add('isActive',null,['label' => 'app.form.blogcategory.is_active'])
            //->add('createdAt',null,['label' => 'app.form.blogcategory.createdAt'])
            ->add('parentCategories',EntityType::class,[
				'class' => BlogCategory::class,
				'choice_label' => 'name',
				'multiple' => true,
				'required' => false,
				'label' => 'app.form.blogcategory.parentCategories'
            ])
            //->add('parentBlogCategories',null,['label' => 'app.form.blogcategory.parentBlogCategories'])
            //->add('blogPosts',null,['label' => 'app.form.blogcategory.blogPosts'])
	        ->add('submit',SubmitType::class,[
				'label'=>'app.form.blogcategory.submit',
		        'attr'=>[
					'class'=>'btn btn-phoenix-success'
		        ]
	        ])
	        
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BlogCategory::class,
	        'translation_domain' => 'forms'
        ]);
    }
}
