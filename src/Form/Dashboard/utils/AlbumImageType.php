<?php

namespace App\Form\Dashboard\utils;

use App\Entity\AlbumImage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AlbumImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
       
            ->add('image', FileType::class, [
				'label' => false,
				'required' => false,
				'attr' => [
					'class' => 'dropzone',
					
				],
			])
        
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AlbumImage::class,
	        'translation_domain' => 'forms'
        ]);
    }
}
