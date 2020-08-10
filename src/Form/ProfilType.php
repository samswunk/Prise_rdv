<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Booking;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class ProfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // dd($options);
        $builder
            ->add('Nom')
            ->add('Prenom')
            ->add('email',EmailType::class)
            ->add('Telephone')
            // ->add('roles')
            // ->add('isVerified')
            ->add('Adresse')
            ->add('CodePostal')
            ->add('Ville')
            ->add('password')
            /*->add('bookings', EntityType::class, [
                // looks for choices from this entity
                'class' => Booking::class,
                // uses the User.username property as the visible option string
                'choice_label' => function ($bookings) 
                    { return date_format($bookings->getStart(),"Y-m-d H:i:s")
                                ." - ".date_format($bookings->getEnd(),"Y-m-d H:i:s")
                                ." - ".$bookings->getDescription();  }
            ])
            // ->add('Bookings',EntityType::class)*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
