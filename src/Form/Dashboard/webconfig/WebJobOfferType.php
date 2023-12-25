<?php

namespace App\Form\Dashboard\webconfig;

use App\Entity\WebJobOffer;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WebJobOfferType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class,[
				'label' => 'app.form.webjoboffer.name'
			
            ])
            ->add('publicTitle',TextType::class,[
				'label' => 'app.form.webjoboffer.publicTitle'
            ])
            ->add('text',CKEditorType::class,[
				'label' => 'app.form.webjoboffer.text',
				'config_name' => 'main_config',
			])
            ->add('enddate',null,[
				'widget' => 'single_text',
	            'label' => 'app.form.webjoboffer.enddate'
			])
            ->add('isActive',ChoiceType::class,[
				'label' => 'app.form.webjoboffer.isActive',
				'choices' => [
					'Yes' => true,
		            'No' => false
	            ]
			])
	       
	
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => WebJobOffer::class,
	        'translation_domain' => 'forms'
        ]);
    }
}
