<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResetPwdType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email',EmailType::class, [
                'attr' => 
                    [   'class'       => 'form-control',
                        'placeholder' => 'Entrez votre email',
                    ],
                    'label' => 'Email de réinitialisation',
                'required'      => true
            ])
            ->add('envoyer',SubmitType::class, [
                'attr' => 
                    [   'class'       => 'btn btn-primary mt-5'
                    ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
