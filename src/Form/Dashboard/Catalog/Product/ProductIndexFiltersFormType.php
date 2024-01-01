<?php
	
	namespace App\Form\Dashboard\Catalog\Product;
	
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
	
	class ProductIndexFiltersFormType extends AbstractType
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
				->add('category',EntityType::class,[
					'label'=>false,
					'class'=>Category::class,
					'placeholder'=>'app.form.catalog.product.category_placeholder',
					'attr'=>[
						'class'=>'form-control',
					],
				])
				->add('isActive',ChoiceType::class,[
					'label'=>false,
					'choices'=>[
						'app.form.catalog.product.isActive_true'=>true,
						'app.form.catalog.product.isActive_false'=>false,
					],
					'attr'=>[
						'class'=>'form-control',
					],
				])
				->add('isDeleted',ChoiceType::class,[
					'label'=>false,
					'choices'=>[
						'app.form.catalog.product.isDeleted_true'=>true,
						'app.form.catalog.product.isActive_false'=>false,
					],
					'attr'=>[
						'class'=>'form-control',
					],
				])
				->add('sku',SearchType::class,[
					'label'=>false,
					'attr'=>[
						'class'=>'form-control',
						'placeholder'=>'app.form.catalog.product.sku_placeholder',
					],
				])
				->add('barCode',SearchType::class,[
					'label'=>false,
					'attr'=>[
						'class'=>'form-control',
						'placeholder'=>'app.form.catalog.product.barCode_placeholder',
					],
				])
				->add('brand',EntityType::class,[
					'label'=>false,
					'placeholder'=>'app.form.catalog.product.brand_placeholder',
					'class'=>Brand::class,
					'attr'=>[
						'class'=>'form-control',
					],
				])
			;
		}
		
		public function configureOptions(OptionsResolver $resolver): void
		{
			$resolver->setDefaults([
				'data_class' => Product::class,
				'translation_domain'=>'forms',
			]);
		}
	}
