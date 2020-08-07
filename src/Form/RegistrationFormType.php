<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', TextType::class, [
                'attr' => 
                    [   'class'       => 'form-control',
                        'placeholder' => 'Entrez un email',
                    ],
                'required'      => true
            ])
            ->add('nom'         , TextType::class, [
                'attr' => 
                    [   'class'       => 'form-control',
                        'placeholder' => 'Entrez un nom',
                    ],
                
                'required'      => true
            ])
            ->add('prenom'      , TextType::class, [
                'attr' => 
                    [   'class'         => 'form-control',
                        'placeholder' => 'Entrez un prénom',
                    ],
                'required'      => false
            ])
            ->add('telephone'   , TextType::class, [
                'attr' => 
                    [   'class'         => 'form-control',
                        'placeholder' => 'Entrez un n° de téléphone valide',
                    ],
                'required'      => true
            ])/**/
            ->add('adresse'     , TextType::class, [
                'attr' => 
                    [   'class'         => 'form-control',
                        'placeholder' => 'Entrez une adresse',
                    ],
                'required'      => false
            ])
            ->add('ville'       , TextType::class, [
                'attr' => 
                    [   'class'         => 'form-control',
                        'placeholder' => 'Entrez une ville',
                    ],
                'required'      => false
            ])
            ->add('codePostal'  , TextType::class, [
                'attr' => 
                    [   'class'         => 'form-control',
                        'placeholder' => 'Entrez un code postal',
                    ],
                'required'      => false
            ])
            /*->add('agreeTerms', CheckboxType::class, [
                'attr' => 
                    [   'class'         => 'form-control',
                        'placeholder' => 'Entrez un code postal',
                    ],
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])*/
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
