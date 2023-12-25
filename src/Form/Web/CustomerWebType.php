<?php

namespace App\Form\Web;

use App\Entity\Customer;
use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class CustomerWebType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', null, [
				'label' => 'app.form.customer.email',
				'constraints' => [
					new NotBlank(),
					new Email(),
				],
	            'attr' => [
	            	'placeholder' => 'app.form.customer.email_placeholder',
	            ],
			])
            ->add('password',PasswordType::class, [
				'label' => 'app.form.customer.password',
				'constraints' => [
					new NotBlank(),
				],
	            'attr' => [
	            	'placeholder' => 'app.form.customer.password_placeholder',
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
            'data_class' => Customer::class,
	        'translation_domain' => 'forms'
        ]);
    }
}
