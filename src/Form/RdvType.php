<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Marque;
use App\Entity\Booking;
use App\Entity\Energie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use App\Form\DataTransformer\DateTimeTransformer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RdvType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //$departement = $this->getDoctrine()->getManager()->getRepository('MonBundle:DepartementFrance')->find($id);
        $user = $options['data'];
        $user = $user->getIdUser();
        
        // dd($user);
        $builder
            ->add('id')
            ->add('title',TextType::class,[
                // 'data' => !empty($user) ? $user->getNom(). " " . $user->getPrenom(). " (" . $user->getTelephone().")" : '',
                // 'empty_data' => !empty($user->getNom()) ? $user->getNom(). " " . $user->getPrenom(). " " . $user->getPrenom() : '',
                'label' => 'Titre',
                'attr' => 
                            [
                                'class' => 'form-control',
                            ]
            ])
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
                        'date_label'=>'Date de début'                       
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
                    'date_label'=>'Date de fin'
                )
            )
            // ->add('background_color',ColorType::class,[
            //     'required' => false,
            //     'attr' => 
            //         [
            //             'class' => 'form-control col-8'
            //         ],
            //     ])
            ->add('description', TextareaType::class,
                [
                'required'   => true,
                'attr' => 
                    [
                        'class' => 'form-control'
                    ],
                ])
            ->add('iduser', EntityType::class, [
                // looks for choices from this entity
                'class' => User::class,
                // uses the User.username property as the visible option string
                'choice_label' => function ($user) 
                    { return $user->getNom()." " . $user->getPrenom() ."
                                " . $user->getEmail()  ." " . $user->getTelephone()  ."
                                " . $user->getAdresse() ." " . $user->getCodePostal()." " . $user->getVille(); 
                        },
                'attr' => 
                    [
                        'class' => 'form-control',
                    ],
                // used to render a select box, check boxes or radios
                'multiple' => false,
                'expanded' => false
            ])
            ->add('marque', EntityType::class, [
                // looks for choices from this entity
                'class' => Marque::class,
                'attr' => 
                    [
                        'class' => 'form-control',
                    ],
                // used to render a select box, check boxes or radios
                'multiple' => false,
                'expanded' => true
            ])
            ->add('energie', EntityType::class, [
                // looks for choices from this entity
                'class' => Energie::class,
                'attr' => 
                    [
                        'class' => 'form-control',
                    ],
                // used to render a select box, check boxes or radios
                'multiple' => false,
                'expanded' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}
