<?php

namespace App\Form;

use App\Entity\Energie;
use Doctrine\DBAL\Types\FloatType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class EnergieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nomEnergie',TextType::class,[
                'label' => 'Nom',
                'attr' => 
                            [
                                'class' => 'form-control',
                            ]
            ])
            ->add('tarifEnergie',NumberType::class,[
                'scale'=>2,
                'label' => 'Tarif',
                'attr' => 
                            [
                                'class' => 'form-control',
                            ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Energie::class,
        ]);
    }
}
