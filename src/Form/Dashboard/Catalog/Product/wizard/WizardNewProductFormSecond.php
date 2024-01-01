<?php
	
	namespace App\Form\Dashboard\Catalog\Product\wizard;
	
	use App\Entity\Product;
	use FOS\CKEditorBundle\Form\Type\CKEditorType;
	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\Extension\Core\Type\TextType;
	use Symfony\Component\Form\FormBuilderInterface;
	use Symfony\Component\OptionsResolver\OptionsResolver;
	use Symfony\UX\Dropzone\Form\DropzoneType;
	
	class WizardNewProductFormSecond extends AbstractType
	{
		public function buildForm(FormBuilderInterface $builder, array $options): void
		{
			$builder
				
				->add('shortDescription',CKEditorType::class,[
					'label' => 'app.form.catalog.product.short_description_label',
					'attr' => [
					
					],
				])
				->add('longDescription',CKEditorType::class,[
					'label' => 'app.form.catalog.product.long_description_label',
					'attr' => [
					
					],
				])
				->add('seoTitle',TextType::class,[
					'label' => 'app.form.catalog.product.seo_title_label',
					'attr' => [
						'placeholder' => 'app.form.catalog.product.seo_title_placeholder',
					],
				])
				->add('seoDescription',TextType::class,[
					'label' => 'app.form.catalog.product.seo_description_label',
					'attr' => [
						'placeholder' => 'app.form.catalog.product.seo_description_placeholder',
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
