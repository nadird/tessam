<?php

namespace App\Form\Dashboard\crm;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentInteractionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('text',null,[
	            'label'=>false,
	            'attr'=>[
	            	'placeholder'=>'app.form.interaction.text_comment',
		            'class'=>'form-control'
	            ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
	        'translation_domain' => 'forms'
        ]);
    }
}
