<?php
	
	namespace App\Form\Dashboard\Catalog\Product\wizard;
	
	use App\Entity\Brand;
	use App\Entity\Category;
	use App\Entity\Product;
	use App\Entity\ProductFamily;
	use App\Entity\ProductType;
	use App\Entity\Tag;
	use Symfony\Bridge\Doctrine\Form\Type\EntityType;
	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
	use Symfony\Component\Form\Extension\Core\Type\TextType;
	use Symfony\Component\Form\FormBuilderInterface;
	use Symfony\Component\OptionsResolver\OptionsResolver;
	
	class WizardNewProductFormFirst extends AbstractType
	{
		public function buildForm(FormBuilderInterface $builder, array $options): void
		{
			$builder
				->add('name',TextType::class,[
					'label' => 'app.form.catalog.product.product_name_label',
					'attr' => [
						'placeholder' => 'app.form.catalog.product.product_name_placeholder',
					],
				])
				->add('type',EntityType::class,[
					'label' => 'app.form.catalog.product.type_label',
					'class' => ProductType::class,
					'attr' => [
						'placeholder' => 'app.form.catalog.product.type_placeholder',
					],
				])
				->add('family',EntityType::class,[
					'label' => 'app.form.catalog.product.family_label',
					'class' => ProductFamily::class,
					'attr' => [
						'placeholder' => 'app.form.catalog.product.family_placeholder',
					],
				])
				->add('sku',TextType::class,[
					'label' => 'app.form.catalog.product.sku_label',
					'attr' => [
						'placeholder' => 'app.form.catalog.product.sku_placeholder',
					],
				])
				->add('barCode',TextType::class,[
					'label' => 'app.form.catalog.product.bar_code_label',
					'attr' => [
						'placeholder' => 'app.form.catalog.product.bar_code_placeholder',
					],
				])
				->add('isActive',CheckboxType::class,[
					'label' => 'app.form.catalog.product.is_active_label',
					'required' => false,
					'attr' => [
						'checked' => false,
					],
				])
				->add('isFeatured',CheckboxType::class,[
					'label' => 'app.form.catalog.product.is_featured_label',
					'required' => false,
					'attr' => [
						'checked' => false,
					],
				])
				->add('isOnSale',CheckboxType::class,[
					'label' => 'app.form.catalog.product.is_on_sale_label',
					'required' => false,
					'attr' => [
						'checked' => false,
					],
				])
				->add('brand', EntityType::class, [
					'class' => Brand::class,
					'label' => 'app.form.catalog.product.brand_label',
					'choice_label' => 'name',
					'autocomplete' => true,
				])
				->add('categories', EntityType::class, [
					'class' => Category::class,
					'label' => 'app.form.catalog.product.categories_label',
					'choice_label' => 'name',
					'multiple' => true,
					'autocomplete' => true,
				])
				->add('mainCategory', EntityType::class, [
					'label' => 'app.form.catalog.product.main_category_label',
					'class' => Category::class,
					'choice_label' => 'name',
				])
				->add('tags', EntityType::class, [
					'label' => 'app.form.catalog.product.tags_label',
					'class' => Tag::class,
					'choice_label' => 'id',
					'multiple' => true,
					'autocomplete' => true,
					'required' => false,
				])
				->add('relatedProducts', EntityType::class, [
					'label' => 'app.form.catalog.product.product_related_to_label',
					'class' => Product::class,
					'choice_label' => 'name',
					'multiple' => true,
					'required' => false,
					'autocomplete' => true,
				])
			;
		}
		
		public function configureOptions(OptionsResolver $resolver): void
		{
			$resolver->setDefaults([
				'data_class' => Product::class,
				'translation_domain' => 'forms',
			]);
		}
	}
