<?php
	
	namespace App\Form\Dashboard\security;
	
	use App\Entity\EmplJobs;
	use App\Entity\Employee;
	use Symfony\Bridge\Doctrine\Form\Type\EntityType;
	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\Extension\Core\Type\EmailType;
	use Symfony\Component\Form\Extension\Core\Type\FileType;
	use Symfony\Component\Form\Extension\Core\Type\PasswordType;
	use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
	use Symfony\Component\Form\Extension\Core\Type\SubmitType;
	use Symfony\Component\Form\FormBuilderInterface;
	use Symfony\Component\OptionsResolver\OptionsResolver;
	
	class EmployeeCreateType extends AbstractType
	{
		public function buildForm(FormBuilderInterface $builder, array $options): void
		{
			$builder
				->add('email',EmailType::class,[
					'label' => 'app.form.employee.email'
				])
				//->add('roles')
				->add('password', RepeatedType::class, [
					'type' => PasswordType::class,
					'invalid_message' => 'app.form.employee.password_mismatch',
					'options' => ['attr' => ['class' => 'password-field']],
					'required' => true,
					'first_options'  => ['label' => 'app.form.employee.password'],
					'second_options' => ['label' => 'app.form.employee.repeat_password'],
				])
				//->add('isActive')
				->add('FirstName',null,[
					'label' => 'app.form.employee.firstname'
				])
				->add('LastName',null,[
					'label' => 'app.form.employee.lastname'
				])
				->add('dob',null,[
					'widget' => 'single_text',
					'label' => 'app.form.employee.dob'
				])
				->add('picture',FileType::class,[
					'mapped' => false,
					'label' => 'app.form.employee.picture'
				])
				->add('job',EntityType::class,[
					'class' => EmplJobs::class,
					'choice_label' => 'name',
					'label' => 'app.form.employee.job'
				])
				->add('submit',SubmitType::class,[
					'label' => 'app.form.employee.submit',
					'attr' => [
						'class' => 'btn btn-primary'
					]
				])
			;
		}
		
		public function configureOptions(OptionsResolver $resolver): void
		{
			$resolver->setDefaults([
				'data_class' => Employee::class,
				'translation_domain' => 'forms'
			]);
		}
	}
