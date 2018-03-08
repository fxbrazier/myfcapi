<?php

namespace AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AppBundle\Entity\ClubStat;


class ClubType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name',
                TextType::class,
                [
                    'constraints' => [
                        new NotBlank(),
                        new Length(['min' => '2', 'max' => '50']),
                    ],
                ]
            )
            ->add('blason',
                TextType::class,
                [
                    'constraints' => [
                        new NotBlank(),
                        new Length(['min' => '2', 'max' => '255']),
                    ],
                ]
            )
            ->add(
                'clubstats',
                EntityType::class,
                [
                    'class'        => ClubStat::class,
                    'multiple'     => true,
                    'by_reference' => false,
                ]
            );
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
             'data_class' => 'AppBundle\Entity\Club',
                'csrf_protection' => false,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_club';
    }


}
