<?php

namespace App\Form\Dashboard\Catalog\Product;

use App\Entity\Brand;
use App\Entity\Category;
use App\Entity\Product;
use App\Entity\ProductFamily;
use App\Entity\ProductType;
use App\Entity\Shop;
use App\Entity\Supplier;
use App\Entity\Tag;
use App\Entity\Tax;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\Dropzone\Form\DropzoneType;

class NewProductFormType extends AbstractType
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
				'placeholder' => 'app.form.catalog.product.family_placeholder',
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
	        /* Handled by Dropzone
	         ->add('images',DropzoneType::class,[
				'label' => 'app.form.catalog.product.images_label',
		        'mapped' => false,
		        'required' => false,
				'multiple' => true,
		        'attr' => [
			        'data-controller' => 'dropzone',
			        'data-dropzone-max-files' => 5,
			        'data-dropzone-max-file-size' => 2,
			        'data-dropzone-accepted-files' => 'image/*',
			        'data-dropzone-add-remove-links' => 'true',
			        'data-dropzone-parallel-uploads' => 1,
			        'data-dropzone-thumbnail-width' => 200,
			        'data-dropzone-thumbnail-height' => 200,
			        'data-dropzone-create-image-thumbnails' => 'true',
			        'data-dropzone-previews-container' => '#dropzone-preview',
			        'data-dropzone-clickable' => 'true',
			        'data-dropzone-headers' => 'X-CSRF-TOKEN',
			        'placeholder' => 'app.form.catalog.product.images_placeholder',
			        'data-url-post' => 'https://httpbin.org/post',
		        ],
	        ])
	         */
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
            ->add('isActive',CheckboxType::class,[
				'label' => 'app.form.catalog.product.is_active_label',
	            'required' => false,
	            'attr' => [
		            'checked' => 'checked',
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
            ->add('availableDate',DateType::class,[
				'widget' => 'single_text',
			])
            ->add('weight',NumberType::class,[
				'label' => 'app.form.catalog.product.weight_label',
	            'attr' => [
		            'placeholder' => 'app.form.catalog.product.weight_placeholder',
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
	        ->add('shops', EntityType::class, [
				'label' => 'app.form.catalog.product.shops_label',
		        'class' => Shop::class,
		        'choice_label' => 'name',
		        'multiple' => true,
		        'autocomplete' => true,
	        ])
            ->add('tags', EntityType::class, [
				'label' => 'app.form.catalog.product.tags_label',
                'class' => Tag::class,
				'choice_label' => 'id',
				'multiple' => true,
	            'autocomplete' => true,
            ])
            ->add('supplier', EntityType::class, [
				'label' => 'app.form.catalog.product.supplier_label',
                'class' => Supplier::class,
				'choice_label' => 'name',
	            'autocomplete' => true,
            ])
            ->add('relatedProducts', EntityType::class, [
				'label' => 'app.form.catalog.product.product_related_to_label',
                'class' => Product::class,
				'choice_label' => 'name',
	            'multiple' => true,
	            'required' => false,
	            'autocomplete' => true,
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
