<?php
	
	namespace App\Form\Dashboard\Catalog\Product\wizard;
	
	use App\Entity\Product;
	use App\Entity\Shop;
	use App\Entity\Tax;
	use Symfony\Bridge\Doctrine\Form\Type\EntityType;
	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\Extension\Core\Type\NumberType;
	use Symfony\Component\Form\FormBuilderInterface;
	use Symfony\Component\OptionsResolver\OptionsResolver;
	
	class WizardNewProductFormFourth extends AbstractType
	{
		public function buildForm(FormBuilderInterface $builder, array $options): void
		{
			$builder
				->add('shops', EntityType::class, [
					'label' => 'app.form.catalog.product.shops_label',
					'class' => Shop::class,
					'choice_label' => 'name',
					'multiple' => true,
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
