<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Marque;
use App\Entity\Booking;
use App\Entity\Energie;
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
                ->add('marque', EntityType::class, [
                    'placeholder' => "Quelle est la marque de votre chaudière ?",
                    'class' => Marque::class,
                    'choice_label'  => 'nomMarque',
                    'attr' => 
                        [
                            'class' => 'form-control',
                        ],
                    // used to render a select box, check boxes or radios
                    'multiple' => false,
                    'expanded' => false
                ])
                ->add('energie', EntityType::class, [
                    'placeholder' => "Quel type d'energie ?",
                    'class' => Energie::class,
                    'choice_label'=>'nomEnergie',
                    'attr' => 
                        [
                            'class' => 'form-control typeEnergie',
                        ],
                    // used to render a select box, check boxes or radios
                    'multiple' => false,
                    'expanded' => false
                ])                
            ->add('isFree', ChoiceType::class, array(
                    'placeholder' => "Souhaitez vous vérouiller le rdv ?",
                    'choices' => array(
                        'Déverouiller (Les utilisateurs pourront choisir ce rdv)' => true, // disponible
                        'Verouiller (Les utilisateurs ne pourront pas choisir ce rdv)' => false
                     ),
                     'attr' => 
                         [
                             'class' => 'form-control disponible'
                         ],
                     'label' => 'Vérouiller/Dévérouiller le rdv',
                 ))
            ->add('isConfirmed', ChoiceType::class, array(
                    'placeholder' => "Souhaitez vous valider le rdv ?",
                    'choices' => array(
                        'Valider' => true,
                        'Avalider' => false
                     ),
                     'attr' => 
                         [
                             'class' => 'form-control confirmation'
                         ],
                     'label' => 'Valider le rendez-vous',
                 ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}
