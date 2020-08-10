<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Booking;
use Doctrine\DBAL\Types\BooleanType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use App\Form\DataTransformer\DateTimeTransformer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class BookingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //$departement = $this->getDoctrine()->getManager()->getRepository('MonBundle:DepartementFrance')->find($id);
        $user = $options['data'];
        // $user = $user->getIdUser();
        // dd($user);
        $builder
            ->add('title', TextType::class, array
            (
                'attr' => 
                    [
                        'class' => 'form-control input-inline'
                    ],
                'label'=>'Titre',
            )
        )
            ->add('start', DateTimeType::class, array
                    (
                        'widget'=> 'single_text',
                        'required' => true,
                        'attr' => 
                            [
                                'class' => 'form-control input-inline datetimepicker',
                                'data-provide' => 'datetimepicker',
                                'format'=> 'dd/MM/yyyy HH:mm',
                                'input' => 'string',
                                'input_format' => 'y-M-d HH:mm:ss'
                            ],
                        'html5'=> false,
                        'format'=> 'dd/MM/yyyy HH:mm', 
                        'label'=>'Date de début'                        
                    )
                )
            ->add('end', DateTimeType::class, array
                (
                    'widget'=> 'single_text',
                    'required' => false,
                    'attr' => 
                        [
                            'class' => 'form-control input-inline datetimepicker',
                            'data-provide' => 'datetimepicker',
                            'format'=> 'dd/MM/yyyy HH:mm',
                            'input' => 'string',
                            'input_format' => 'y-M-d HH:mm:ss'
                        ],
                    'html5'=> false,
                    'format'=> 'dd/MM/yyyy HH:mm', 
                    'label'=>'Date de fin'
                )
            )
            ->add('background_color',ColorType::class,[
                'required' => false,
                'attr' => 
                    [
                        'class' => 'form-control'
                    ], 
                'label'=>'Couleur'
                ])
            ->add('description', TextareaType::class,
                [
                'required'   => false,
                'attr' => 
                    [
                        'class' => 'form-control'
                    ],
                ])
                
            ->add('isFree', ChoiceType::class, array(
                    'choices' => array(
                        'Oui' => true,
                        'Non' => false
                     ),
                     'attr' => 
                         [
                             'class' => 'form-control disponible'
                         ],
                     'label' => 'Disponible',
                    //  'required' => true,
                    //  'empty_value' => false,
                    //  'choices_as_values' => true
                 ))
            // ->add('iduser', EntityType::class, [
            //     // looks for choices from this entity
            //     'class' => User::class,
            //     // uses the User.username property as the visible option string
            //     'choice_label' => function ($user) 
            //         { return $user->getNom()." " . $user->getPrenom() ."
            //                     " . $user->getEmail()  ." " . $user->getTelephone()  ."
            //                     " . $user->getAdresse() ." " . $user->getCodePostal()." " . $user->getVille(); 
            //             },
            //     // used to render a select box, check boxes or radios
            //     'multiple' => false,
            //     'expanded' => false
            // ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}
