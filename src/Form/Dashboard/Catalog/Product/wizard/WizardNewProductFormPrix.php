<?php
	
	namespace App\Form\Dashboard\Catalog\Product\wizard;
	
	use App\Entity\Product;
	use App\Entity\Tax;
	use Symfony\Bridge\Doctrine\Form\Type\EntityType;
	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\Extension\Core\Type\NumberType;
	use Symfony\Component\Form\FormBuilderInterface;
	use Symfony\Component\OptionsResolver\OptionsResolver;
	
	class WizardNewProductFormPrix extends AbstractType
	{
		public function buildForm(FormBuilderInterface $builder, array $options): void
		{
			$builder
				->add('supplier',EntityType::class,[
					'label' => 'app.form.catalog.product.supplier_label',
					'class' => Product::class,
					'choice_label' => 'name',
					'placeholder' => 'app.form.catalog.product.supplier_placeholder',
					'multiple' => false,
					'required' => false,
				])
				->add('tax', EntityType::class, [
					'label' => 'app.form.catalog.product.tax_label',
					'class' => Tax::class,
					'choice_label' => 'name',
					'choice_value' => 'rate',
					'placeholder' => 'app.form.catalog.product.tax_placeholder',
					'multiple' => false,
					'required' => false,
				])
				->add('supplyPriceHt',NumberType::class,[
					'label' => 'app.form.catalog.product.supply_price_ht_label',
					'required' => false,
					'attr' => [
						'placeholder' => 'app.form.catalog.product.supply_price_ht_placeholder',
					],
				])
				->add('supplyPriceTtc',NumberType::class,[
					'label' => 'app.form.catalog.product.supply_price_ttc_label',
					'required' => false,
					'attr' => [
						'placeholder' => 'app.form.catalog.product.supply_price_ttc_placeholder',
						'disabled' => 'disabled',
					],
				])
				->add('priceHt',NumberType::class,[
					'label' => 'app.form.catalog.product.price_ht_label',
					'required' => false,
					'attr' => [
						'placeholder' => 'app.form.catalog.product.price_ht_placeholder',
					],
				])
				->add('priceTtc',NumberType::class,[
					'label' => 'app.form.catalog.product.price_ttc_label',
					'required' => false,
					'attr' => [
						'placeholder' => 'app.form.catalog.product.price_ttc_placeholder',
						'disabled' => 'disabled',
					],
				])
				->add('wholeSalePriceHt',NumberType::class,[
					'label' => 'app.form.catalog.product.whole_sale_price_ht_label',
					'required' => false,
					'attr' => [
						'placeholder' => 'app.form.catalog.product.whole_sale_price_ht_placeholder',
					],
				])
				->add('wholeSalePriceTtc',NumberType::class,[
					'label' => 'app.form.catalog.product.whole_sale_price_ttc_label',
					'required' => false,
					'attr' => [
						'placeholder' => 'app.form.catalog.product.whole_sale_price_ttc_placeholder',
						'disabled' => 'disabled',
					],
				])
				->add('wholeSaleMoq',NumberType::class,[
					'label' => 'app.form.catalog.product.whole_sale_moq_label',
					'required' => false,
					'attr' => [
						'placeholder' => 'app.form.catalog.product.whole_sale_moq_placeholder',
					],
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
