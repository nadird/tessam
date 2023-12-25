<?php

namespace App\Form\Web;

use App\Entity\WebContact;
use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WebContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',null,[
				'label' => false,
	            'attr' => [
	            	'placeholder' => 'app.form.webcontact.name',
		            'class' => 'form-control',
	            ],
			])
            ->add('email',null,[
	            'label' => false,
	            'attr' => [
		            'placeholder' => 'app.form.webcontact.email',
		            'class' => 'form-control',
	            ],
			])
            ->add('phone',null,[
	            'label' => false,
	            'attr' => [
		            'placeholder' => 'app.form.webcontact.phone',
		            'class' => 'form-control',
	            ],
	            ])
            ->add('subject',null,[
	            'label' => false,
	            'attr' => [
		            'placeholder' => 'app.form.webcontact.subject',
		            'class' => 'form-control',
	            ],
	            ])
            ->add('message',null,[
	            'label' => false,
	            'attr' => [
		            'placeholder' => 'app.form.webcontact.message',
		            'class' => 'form-control',
	            ],
	            ])
	        ->add('submit',SubmitType::class,[
		        'label' => 'app.form.webcontact.submit',
		        'attr' => [
			        'class' => 'btn btn-primary btn-lg',
			        'style' => 'width: -webkit-fill-available;',
			        'value' => 'Create',
		        ],
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
            'data_class' => WebContact::class,
	        'translation_domain' => 'forms'
        ]);
    }
}
