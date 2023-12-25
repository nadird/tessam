<?php

namespace App\Form\Dashboard\crm\customers;

use App\Entity\CFormatBien;
use App\Entity\Customer;
use App\Entity\CustomerState;
use App\Entity\Employee;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomerFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', TextType::class, [
	            'label' => 'app.form.product.email',
	            'required' => true,
	            'attr' => [
		            'placeholder' => 'app.form.product.email_placeholder'
	            ]
			])
            ->add('isVerified', ChoiceType::class, [
	            'label' => 'app.form.product.is_verified',
	            'expanded'=>false,
	            'multiple'=>false,
	            'required'=>true,
	            'choices'=>[
		            'app.form.product.is_verified_true'=>true,
		            'app.form.product.is_verified_false'=>false
	            ],
	            'attr'=>[
		            'class'=>'custom-control-input'
	            ]
	            
			])
            ->add('firstname', TextType::class, [
	            'label' => 'app.form.product.firstname',
	            'required' => true,
	            'attr' => [
		            'placeholder' => 'app.form.product.firstname_placeholder'
	            ]
            ])
            ->add('lastname',TextType::class,[
				'label' => 'app.form.product.lastname',
	            'required' => true,
	            'attr' => [
					'placeholder' => 'app.form.product.lastname_placeholder'
	            ]
            ])
            ->add('phone',TextType::class,[
				'label' => 'app.form.product.phone',
	            'required' => true,
	            'attr' => [
					'placeholder' => 'app.form.product.phone_placeholder'
	            ]
            ])
            ->add('Address',TextType::class,[
				'label' => 'app.form.product.address',
	            'required' => true,
	            'attr' => [
					'placeholder' => 'app.form.product.address_placeholder'
	            ]
            ])
            ->add('picture',FileType::class,[
				'label' => 'app.form.product.picture',
	            'required' => false,
				'mapped' => false,
	            'attr' => [
					'placeholder' => 'app.form.product.picture_placeholder'
	            ]
            ])
            ->add('isActive',ChoiceType::class, [
	            'label' => 'app.form.product.is_active',
	            'expanded'=>false,
	            'multiple'=>false,
	            'required'=>false,
	            'choices'=>[
		            'app.form.product.is_active_true'=>true,
		            'app.form.product.is_active_false'=>false
	            ],
	            'attr'=>[
		            'class'=>'custom-control-input'
	            ]
	        ])
            ->add('isDeleted',ChoiceType::class, [
	            'label' => 'app.form.product.is_deleted',
	            'expanded'=>false,
	            'multiple'=>false,
	            'required'=>true,
	            'choices'=>[
		            'app.form.product.is_deleted_true'=>true,
		            'app.form.product.is_deleted_false'=>false
	            ],
	            'attr'=>[
		            'class'=>'custom-control-input'
	            ]
            ])
            ->add('dob', DateType::class, [
	            'label' => 'app.form.product.dob',
	            'widget' => 'single_text',
	            'html5' => true,
	            'required' => false
			])
            ->add('actNaissance',TextType::class,[
				'label' => 'app.form.product.act_naissance',
	            'required' => true,
	            'attr' => [
					'placeholder' => 'app.form.product.act_naissance_placeholder'
	            ]
            ])
            ->add('pi',TextType::class,[
				'label' => 'app.form.product.pi',
	            'required' => true,
	            'attr' => [
					'placeholder' => 'app.form.product.pi_placeholder'
	            ]
            ])
            ->add('state',EntityType::class,[
	            'class' => CustomerState::class,
				'label' => 'app.form.product.state',
	            'required' => true,
	            'placeholder' => 'app.form.product.state_placeholder'
            ])
            ->add('employee',EntityType::class,[
				'label' => 'app.form.product.employee',
	            'required' => true,
	            'class' => Employee::class,
	            'placeholder' => 'app.form.product.employee_placeholder'
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
