<?php
	
	namespace App\Form\Dashboard\Catalog\Category;
	
	use App\Entity\Brand;
	use App\Entity\Category;
	use App\Entity\Product;
	use Symfony\Bridge\Doctrine\Form\Type\EntityType;
	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
	use Symfony\Component\Form\Extension\Core\Type\SearchType;
	use Symfony\Component\Form\Extension\Core\Type\TextType;
	use Symfony\Component\Form\FormBuilderInterface;
	use Symfony\Component\OptionsResolver\OptionsResolver;
	
	class CategoryIndexFiltersFormType extends AbstractType
	{
		public function buildForm(FormBuilderInterface $builder, array $options): void
		{
			$builder
				->add('name',TextType::class,[
					'label'=> false,
					'attr'=>[
						'class'=>'form-control',
						'placeholder'=>'app.form.catalog.product.name_placeholder',
					],
				])
				->add('isActive',ChoiceType::class,[
					'label'=>false,
					'required'=>false,
					'choices'=>[
						'app.form.catalog.product.isActive_true'=>true,
						'app.form.catalog.product.isActive_false'=>false,
					],
					'placeholder'=>'app.form.catalog.category.isActive_placeholder',
					'attr'=>[
						'class'=>'form-control',
						
					],
				])
				->add('isDeleted',ChoiceType::class,[
					'label'=>false,
					'required'=>false,
					'placeholder'=>'app.form.catalog.category.isDeleted_placeholder',
					'choices'=>[
						'app.form.catalog.category.isDeleted_true'=>true,
						'app.form.catalog.category.isDeleted_false'=>false,
					],
					'attr'=>[
						'class'=>'form-control',
					],
				])
			;
		}
		
		public function configureOptions(OptionsResolver $resolver): void
		{
			$resolver->setDefaults([
				'data_class' => null,
				'translation_domain'=>'forms',
			]);
		}
	}
