<?php

namespace App\Form\Web;

use App\Entity\Customer;
use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', null, [
	            'label' => 'Email',
				//'constraints' => [],
	            'attr' => [
	            	'placeholder' => 'app.form.customer.email',
	            ]
            ])
	        ->add('FirstName',null,[
				'label' => 'app.form.customer.firstname',
		        'attr' => [
		        	'placeholder' => 'app.form.customer.firstname_placeholder',
		        ],
	        ])
	        ->add('LastName',null,[
				'label' => 'app.form.customer.lastname',
		        'attr' => [
		        	'placeholder' => 'app.form.customer.lastname_placeholder',
		        ],
	        ])
	        ->add('phone',null,[
				'label' => 'app.form.customer.phone',
		        'attr' => [
		        	'placeholder' => 'app.form.customer.phone_placeholder',
		        ],
	        ])
	        ->add('address',null,[
				'label' => 'app.form.customer.address',
		        'attr' => [
		        	'placeholder' => 'app.form.customer.address_placeholder',
		        ],
	        ])
	        ->add('picture',FileType::class,[
				'mapped' => false,
		        'required' => false,
		        'label' => 'app.form.customer.picture',
	        ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
				'label' => 'app.form.customer.agreeTerms',
                'constraints' => [
                    new IsTrue([
                        'message' => 'app.form.customer.agreeTermsMessage',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'app.form.customer.passwordBlankMessage',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => ' {{ limit }} app.form.customer.passwordLengthMessage',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
	        ->add('submit',SubmitType::class,[
				'label' => 'app.form.customer.submit',
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
            'data_class' => Customer::class,
	        'csrf_protection' => true,
	        'csrf_field_name' => '_token',
	        'csrf_token_id'   => 'registration_form',
	        'translation_domain' => 'forms'
        ]);
    }
}
