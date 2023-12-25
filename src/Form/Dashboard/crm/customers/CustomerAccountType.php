<?php
	
	namespace App\Form\Dashboard\crm\customers;
	
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
	
	class CustomerAccountType extends AbstractType
	{
		public function buildForm(FormBuilderInterface $builder, array $options): void
		{
			$builder
				->add('password', RepeatedType::class, [
					'type' => PasswordType::class,
					'invalid_message' => 'app.form.employee.password_mismatch',
					'options' => ['attr' => ['class' => 'password-field']],
					'required' => true,
					'first_options'  => [
						'label' => 'app.form.customer.password',
						'attr'=>[
							'placeholder'=>'app.form.customer.password'
						]
					],
					'second_options' => [
						'label' => 'app.form.customer.repeat_password',
						'attr'=>[
							'placeholder'=>'app.form.customer.password'
						]
						
					],
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
