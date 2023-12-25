<?php
	
	namespace App\Form\Customer;
	
	use App\Entity\Customer;
	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\Extension\Core\Type\DateType;
	use Symfony\Component\Form\Extension\Core\Type\FileType;
	use Symfony\Component\Form\Extension\Core\Type\PasswordType;
	use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
	use Symfony\Component\Form\Extension\Core\Type\SubmitType;
	use Symfony\Component\Form\Extension\Core\Type\TextType;
	use Symfony\Component\Form\FormBuilderInterface;
	use Symfony\Component\OptionsResolver\OptionsResolver;
	
	class CustomerFinishRegistration extends AbstractType
	{
		public function buildForm(FormBuilderInterface $builder, array $options): void
		{
			$builder
				/*
				 ->add('firstname',TextType::class,[
					'label'=>false,
					'required'=>false,
					'attr'=>[
						'placeholder'=>'app.form.customer.firstname',
						'class'=>'form-control',
						'disabled'=>true
					]
				])
				->add('lastname',TextType::class,[
					'label'=>false,
					'required'=>false,
					'attr'=>[
						'placeholder'=>'app.form.customer.lastname',
						'class'=>'form-control',
						'disabled'=>true
					]
				])
				->add('email',TextType::class,[
					'label'=>false,
					'required'=>false,
					'attr'=>[
						'placeholder'=>'app.form.customer.email',
						'class'=>'form-control',
						'disabled'=>true
					]
				])
				->add('phone',TextType::class,[
					'label'=>false,
					'required'=>false,
					'attr'=>[
						'placeholder'=>'app.form.customer.name',
						'class'=>'form-control',
						'disabled'=>true,
					]
				])
				 */
				->add('address',null,[
					'label'=>false,
					'mapped'=>false,
					'required'=>false,
					'attr'=>[
						'placeholder'=>'app.form.customer.address',
						'class'=>'form-control',
						
					]
				])
				->add('picture',FileType::class,[
					'label'=>'app.form.customer.picture',
					'mapped'=>false,
					'required'=>false,
					'attr'=>[
						'class'=>'form-control'
					]
				])
				->add('password', RepeatedType::class, [
					'type' => PasswordType::class,
					'invalid_message' => 'app.form.employee.password_mismatch',
					'options' => ['attr' => ['class' => 'password-field']],
					'required' => true,
					'first_options'  => ['label' => 'app.form.customer.password'],
					'second_options' => ['label' => 'app.form.customer.repeat_password'],
				])
				->add('pi',TextType::class,[
					'label'=>'app.form.customer.pi',
					'attr'=>[
						'placeholder'=>'app.form.customer.pi',
						'class'=>'form-control',
					]
				])
				->add('dob',DateType::class,[
					'label'=>'app.form.customer.dob',
					'widget'=>'single_text',
					'attr'=>[
						'placeholder'=>'app.form.customer.dob',
						'class'=>'form-control',
						'min'=>date('Y-m-d',strtotime('-75 years')),
						'max'=>date('Y-m-d',strtotime('-18 years')),
					]
				])
				->add('actNaissance',TextType::class,[
					'label'=>false,
					'attr'=>[
						'placeholder'=>'app.form.customer.actNaissance',
						'class'=>'form-control',
					]
				])
				->add('submit',SubmitType::class,[
						'label'=>'app.form.customer.submit',
						'attr'=>[
							'class'=>'btn btn-primary'
						]]
				)
			;
		}
		
		public function configureOptions(OptionsResolver $resolver): void
		{
			$resolver->setDefaults([
				'data_class' => Customer::class,
				'csrf_protection'=>true,
				'translation_domain' => 'forms'
			]);
		}
	}
