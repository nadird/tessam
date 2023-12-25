<?php
	
	namespace App\Form\Dashboard\crm\customers;
	
	use App\Entity\CFormatBien;
	use App\Entity\ContactState;
	use App\Entity\CustomerState;
	use App\Entity\InteractionHistory;
	use App\Entity\InteractionSource;
	use App\Entity\InteractionType;
	use App\Entity\LeadState;
	use Symfony\Bridge\Doctrine\Form\Type\EntityType;
	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
	use Symfony\Component\Form\Extension\Core\Type\SubmitType;
	use Symfony\Component\Form\Extension\Core\Type\TextType;
	use Symfony\Component\Form\FormBuilderInterface;
	use Symfony\Component\OptionsResolver\OptionsResolver;
	
	class CustomerInterestType extends AbstractType
	{
		public function buildForm(FormBuilderInterface $builder, array $options): void
		{
			$builder
				->add('format',EntityType::class,[
					'class' => CFormatBien::class,
					'label' => false,
					'placeholder' => 'app.form.interest.format',
					'attr' => [
						'placeholder' => 'Select Format'
					]
				])
				->add('submit',SubmitType::class,[
					'label' => 'app.form.interaction.submit',
					'attr' => [
						'class' => 'btn btn-phoenix-primary'
					]
				])
				
				
			;
		}
		
		public function configureOptions(OptionsResolver $resolver): void
		{
			$resolver->setDefaults([
				'translation_domain' => 'forms'
			]);
		}
	}
