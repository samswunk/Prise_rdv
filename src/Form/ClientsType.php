<?php

namespace App\Form;

use App\Entity\Clients;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;

class ClientsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom'         , TextType::class, [
                'attr' => 
                    [   'class'       => 'form-control col-8',
                        'placeholder' => 'Entrez un nom',
                    ],
                
                'required'      => true
            ])
            ->add('prenom'      , TextType::class, [
                'attr' => 
                    [   'class'         => 'form-group col-8',
                        'placeholder' => 'Entrez un prénom',
                    ],
                'required'      => false
            ])
            ->add('telephone'   , TelType::class, [
                'attr' => 
                    [   'class'         => 'form-group col-8',
                        'placeholder' => 'Entrez un n° de téléphone valide',
                    ],
                'required'      => true
            ])/**/
            ->add('adresse'     , TextType::class, [
                'attr' => 
                    [   'class'         => 'form-group col-8',
                        'placeholder' => 'Entrez une adresse',
                    ],
                'required'      => false
            ])
            ->add('ville'       , TextType::class, [
                'attr' => 
                    [   'class'         => 'form-group col-8',
                        'placeholder' => 'Entrez une ville',
                    ],
                'required'      => false
            ])
            ->add('codePostal'  , CountryType::class, [
                'attr' => 
                    [   'class'         => 'form-group col-8',
                        'placeholder' => 'Entrez un code postal',
                    ],
                'required'      => false
            ])/**/
            // ->add('rdv')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Clients::class,
        ]);
    }
}
