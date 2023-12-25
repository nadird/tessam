<?php

namespace App\Form\Web;

use App\Entity\WebJobApplication;

use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WebJobAppicationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname',null,[
				'label' => 'app.form.webjobapplication.firstname',
	            'attr' => [
	            	'placeholder' => 'app.form.webjobapplication.firstname',
	            ],
				])
            ->add('lastname',null,[
				'label' => 'app.form.webjobapplication.lastname',
	            'attr' => [
	            	'placeholder' => 'app.form.webjobapplication.lastname',
	            ],
            ])
	        ->add('email',null,[
				'label' => 'app.form.webjobapplication.email',
	            'attr' => [
	            	'placeholder' => 'app.form.webjobapplication.email',
	            ],
	        ])
            ->add('phone',null,[
				'label' => 'app.form.webjobapplication.phone',
	            'attr' => [
	            	'placeholder' => 'app.form.webjobapplication.phone',
	            ],
			
            ])
            ->add('dob',null,[
				'widget' => 'single_text',
	            'label' => 'app.form.webjobapplication.dob',
			])
            ->add('address',null,[
				'label' => 'app.form.webjobapplication.address',
	            'attr' => [
	            	'placeholder' => 'app.form.webjobapplication.address',
	            ],
            ])
            ->add('resume',FileType::class,[
				'mapped' => false,
	            'label' => 'app.form.webjobapplication.resume',
			])
            ->add('linkedin',null,[
				'label' => 'app.form.webjobapplication.linkedin',
	            'attr' => [
	            	'placeholder' => 'app.form.webjobapplication.linkedin',
	            ],
            ])
            ->add('additionalInfos',TextareaType::class,[
				'required' => false,
	            'label' => 'app.form.webjobapplication.additionalInfos',
			])
	        ->add('submit',SubmitType::class,[
	        	'label' => 'app.form.webjobapplication.submit',
		        'attr' => [
		        	'class' => 'btn btn-primary'
		        ]
	        ])
	        ->add('captcha', Recaptcha3Type::class, [
		        'constraints' => new Recaptcha3(),
		        'action_name' => 'formation',
	        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => WebJobApplication::class,
	        'translation_domain' => 'forms',
	        'csrf_protection' => true,
	        'csrf_field_name' => '_token',
	        'csrf_token_id' => 'web_job_application',
	        
        ]);
    }
}
