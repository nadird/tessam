<?php

namespace App\Form\Dashboard\crm\customers;

use App\Entity\Cbien;
use App\Entity\Order;
use App\Form\Dashboard\crm\orders\AutocompleteBienType;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderCustomerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            //->add('orderState')
	         ->add('bien',EntityType::class,[
		        'class' => Cbien::class,
		         'query_builder' => function (EntityRepository $er): QueryBuilder {
			         return $er->createQueryBuilder('b')
				         //->andWhere('b.isDeleted = false')
				         ->andWhere('b.isSold = false')
				         ->andWhere('b.state = 1')
				         ->andWhere('b.isHold = false')
				         ;
		         },
		        //'choice_label' => 'name',
		        'multiple' => false,
		        'expanded' => false,
		        'mapped' => false,
	            'autocomplete' => 'true',
	        ])
	        ->add('reduction',NumberType::class,[
		        'mapped' => false,
		        'required' => false,
		        'label'=> 'app.form.order.reduction',
		        'attr' => [
			        'placeholder' => 'app.form.order.reduction_placeholder',
		        ],
		        //'attr' => ['readonly' => true] // to prevent manual editing
	        
	        ])
	        ->add('finalPrice', IntegerType::class, [
		        'mapped' => false, // because finalPrice might not be a direct property of the Order entity
		        'label'=> 'app.form.order.final_price',
		        'attr' => [
			        'placeholder' => 'app.form.order.final_price_placeholder',
			        'readonly' => true
		        ],
		        //'attr' => ['readonly' => true] // to prevent manual editing
	        ])
	        
	        ->add('orderType',EntityType::class,[
		        'class' => \App\Entity\OrderType::class,
		        'choice_label' => 'name',
				'label'=> 'app.form.order.type',
		        'multiple' => false,
		        'expanded' => false,
		        'mapped' => false,
	        ])
	        ->add('credit',NumberType::class,[
		        'label'=> false,
				//'currency' => 'DZD',
				'mapped' => false,
				'required' => false,
		        'attr' => [
			        'placeholder' => 'app.form.order.credit_placeholder',
		        ],
		      
	        ])
	        ->add('bank',EntityType::class,[
		        'class' => \App\Entity\Bank::class,
		        'label'=> false,
		        'multiple' => false,
		        'expanded' => false,
		        'mapped' => false,
		        'placeholder' => 'app.form.order.select_bank_placeholder',
		        'attr'=>[
		        	'class'=>'select2',
			        'placeholder' => 'app.form.order.select_bank_placeholder',
		        ]
	        ])
	        ->add('submit', SubmitType::class, [
		        'label' => 'app.form.order.submit',
		        'attr' => [
			        'class' => 'btn btn-primary btn-block'
		        ]
	        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
	        'translation_domain' => 'forms'
        ]);
    }
}
