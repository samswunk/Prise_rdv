<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class MultiType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder    ->add('start', DateTimeType::class, 
                    [
                        'widget'=> 'single_text',
                        'required' => true,
                        'attr' => 
                            [
                                'class' => 'form-control input-inline datepicker',
                                'data-provide' => 'datepicker',
                                'format'=> 'dd/MM/yyyy HH:mm',
                                'input' => 'string',
                                'input_format' => 'y-M-d HH:mm:ss'
                            ],
                        'html5'=> false,
                        'format'=> 'dd/MM/yyyy HH:mm', 
                        'label'=>'Date de début'
                    ]
                )
                ->add('end', DateTimeType::class, [
                    'widget'=> 'single_text',
                    'required' => false,
                    'attr' => 
                        [
                            'class' => 'form-control input-inline datepicker',
                            'data-provide' => 'datepicker',
                            'format'=> 'dd/MM/yyyy HH:mm',
                            'input' => 'string',
                            'input_format' => 'y-M-d HH:mm:ss'
                        ],
                    'html5'=> false,
                    'format'=> 'dd/MM/yyyy HH:mm', 
                    'label'=>'Date de fin'
                    ]
                )
                ->add('startHour', DateTimeType::class, [
                        'widget'=> 'single_text',
                        'required' => true,
                        'attr' => 
                            [
                                'class' => 'form-control input-inline timepicker',
                                'data-provide' => 'timepicker',
                                'format'=> 'HH:mm',
                                'input' => 'string',
                                'input_format' => 'HH:mm:ss'
                            ],
                        'html5'=> false,
                        'format'=> 'HH:mm', 
                        'label'=>'Heure de début'
                    ]
                )
                ->add('endHour', DateTimeType::class, 
                    [
                    'widget'=> 'single_text',
                    'required' => false,
                    'attr' => 
                        [
                            'class' => 'form-control input-inline timepicker',
                            'data-provide' => 'timepicker',
                            'format'=> 'HH:mm',
                            'input' => 'string',
                            'input_format' => 'HH:mm:ss'
                        ],
                    'html5'=> false,
                    'format'=> 'HH:mm', 
                    'label'=>'Heure de fin'
                    ]
                )
                ->add('repetition', IntegerType::class, 
                    [
                    'attr' => 
                        [
                            'class' => 'form-control'
                        ],
                    'label' => 'Nbr de répétition',
                    ]
                )
                ->add('jours', ChoiceType::class, 
                    [
                    'attr' => 
                        [
                            'class' => 'form-control'
                        ],
                    'label' => 'Jours',
                    'choices' => [ 'lundi' => 'lundi',
                                        'mardi' => 'mardi',
                                        'mercredi' => 'mercredi',
                                        'jeudi' => 'jeudi',
                                        'vendredi' => 'vendredi',
                                        'samedi' => 'samedi',
                                        'dimanche' => 'dimanche',
                    ],
                // used to render a select box, check boxes or radios
                'multiple' => true,
                'expanded' => true
                    ]
                )
                ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
