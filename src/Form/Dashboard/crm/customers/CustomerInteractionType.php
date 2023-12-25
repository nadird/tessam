<?php
	
	namespace App\Form\Dashboard\crm\customers;
	
	use App\Entity\CustomerState;
	use App\Entity\InteractionHistory;
	use App\Entity\InteractionSource;
	use App\Entity\InteractionType;
	use App\Entity\LeadState;
	use Symfony\Bridge\Doctrine\Form\Type\EntityType;
	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
	use Symfony\Component\Form\Extension\Core\Type\SubmitType;
	use Symfony\Component\Form\Extension\Core\Type\TextareaType;
	use Symfony\Component\Form\Extension\Core\Type\TextType;
	use Symfony\Component\Form\FormBuilderInterface;
	use Symfony\Component\OptionsResolver\OptionsResolver;
	
	class CustomerInteractionType extends AbstractType
	{
		public function buildForm(FormBuilderInterface $builder, array $options): void
		{
			$builder
				->add('text',TextareaType::class,[
					'label' => false,
					'attr' => [
						'placeholder' => 'app.form.interaction.text',
						'class' => 'form-control',
						'rows' => 5
					]
				])
				->add('source',EntityType::class,[
					'class' => InteractionSource::class,
					'choice_label' => 'name',
					'label' => false,
					'placeholder' => 'app.form.interaction.source',
					'attr'=>[
						'class' => 'form-control',
						'placeholder' => 'app.form.interaction.source'
					]
				])
				->add('type',EntityType::class,[
					'class' => InteractionType::class,
					'choice_label' => 'name',
					'label' => false,
					'placeholder' => 'app.form.interaction.type',
					'attr'=>[
						'class' => 'form-control',
						'placeholder' => 'app.form.interaction.type'
					]
				])
				->add('customerState',EntityType::class,[
					'class' => CustomerState::class,
					'choice_label' => 'name',
					'label' => false,
					'placeholder' => 'app.form.interaction.customer_state',
					'attr'=>[
						'class' => 'form-control',
						'placeholder' => 'app.form.interaction.customer_state'
					]
				])
				
			;
		}
		
		public function configureOptions(OptionsResolver $resolver): void
		{
			$resolver->setDefaults([
				'data_class' => InteractionHistory::class,
				'translation_domain' => 'forms'
			]);
		}
	}
