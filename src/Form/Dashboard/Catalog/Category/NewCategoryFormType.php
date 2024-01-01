<?php
	
	namespace App\Form\Dashboard\Catalog\Category;
	
	use App\Entity\Brand;
	use App\Entity\Category;
	use App\Entity\Product;
	use FOS\CKEditorBundle\Form\Type\CKEditorType;
	use Symfony\Bridge\Doctrine\Form\Type\EntityType;
	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
	use Symfony\Component\Form\Extension\Core\Type\SearchType;
	use Symfony\Component\Form\Extension\Core\Type\SubmitType;
	use Symfony\Component\Form\Extension\Core\Type\TextareaType;
	use Symfony\Component\Form\Extension\Core\Type\TextType;
	use Symfony\Component\Form\FormBuilderInterface;
	use Symfony\Component\OptionsResolver\OptionsResolver;
	
	class NewCategoryFormType extends AbstractType
	{
		public function buildForm(FormBuilderInterface $builder, array $options): void
		{
			$builder
				->add('name',TextType::class,[
					'label'=> 'app.form.catalog.category.name',
					'attr'=>[
						'class'=>'form-control',
						'placeholder'=>'app.form.catalog.category.name_placeholder',
					],
				])
				->add('shortDescription',CKEditorType::class,[
					'label'=> 'app.form.catalog.category.shortDescription',
					'attr'=>[
						'class'=>'form-control',
						'placeholder'=>'app.form.catalog.category.shortDescription_placeholder',
					],
				])
				->add('longDescription',CKEditorType::class,[
					'label'=> 'app.form.catalog.category.longDescription',
					'attr'=>[
						'class'=>'form-control',
						'placeholder'=>'app.form.catalog.category.longDescription_placeholder',
					],
				])
				->add('parent',EntityType::class,[
					'label'=> 'app.form.catalog.category.parent',
					'class'=>Category::class,
					'autocomplete'=>'true',
					'required'=>false,
					'attr'=>[
						'class'=>'form-control',
					],
				])
				->add('seoTitle',TextType::class,[
					'label'=> 'app.form.catalog.category.seoTitle',
					'required'=>false,
					'attr'=>[
						'class'=>'form-control',
						'placeholder'=>'app.form.catalog.category.seoTitle_placeholder',
					],
				])
				->add('seoDescription',TextareaType::class,[
					'label'=> 'app.form.catalog.category.seoDescription',
					'required'=>false,
					'attr'=>[
						'class'=>'form-control',
						'placeholder'=>'app.form.catalog.category.seoDescription_placeholder',
					],
				])
				->add('isActive',ChoiceType::class,[
					'label'=>'app.form.catalog.category.isActive',
					'choices'=>[
						'app.form.catalog.category.isActive_true'=>true,
						'app.form.catalog.category.isActive_false'=>false,
					],
					'attr'=>[
						'class'=>'form-control',
					],
				])
				->add('submit',SubmitType::class,[
					'label'=>'app.form.catalog.category.submit',
					'attr'=>[
						'class'=>'btn btn-phoenix-primary btn-block',
					],
				])
			;
		}
		
		public function configureOptions(OptionsResolver $resolver): void
		{
			$resolver->setDefaults([
				'data_class' => Category::class,
				'translation_domain'=>'forms',
			]);
		}
	}
