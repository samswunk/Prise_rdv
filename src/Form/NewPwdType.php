<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class NewPwdType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // ->add('pass',PasswordType::class, [
            //     'attr' => 
            //         [   'class'       => 'form-control',
            //             'placeholder' => 'Entrez votre nouveau mdp',
            //         ],
            //         'label' => 'Entrez votre nouveau mot de passe',
            //     'required'      => true
            // ])
            ->add('pass', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'label' => 'Entrez votre nouveau mot de passe',
                'attr' => 
                    [   'class'         => 'form-control',
                        'placeholder' => 'Entrez un mdp VALIDE',
                    ],
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de saisir un mdp',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mdp doit contenir au moins {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('token', HiddenType::class)
            ->add('envoyer',SubmitType::class, [
                'attr' => 
                    [   'class'       => 'btn btn-primary mt-5'
            ], 'label'=> 'Réinitialiser'
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
