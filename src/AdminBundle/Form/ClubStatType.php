<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ClubStatType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'label',
                TextType::class,
                [
                    'constraints' => [
                        new NotBlank(),
                    ],
                ]
            )
            ->add(
                'shortLabel',
                TextType::class,
                [
                    'constraints' => [
                        new NotBlank(),
                    ],
                ]
            )
            ->add(
                'value',
                TextType::class,
                [
                    'constraints' => [
                        new NotBlank(),
                    ],
                ]
            )
            ->add(
                'club',
                null,
                [
                    'constraints' => [
                        new NotBlank(),
                    ],
                ]
            );
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\ClubStat'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_clubstat';
    }


}
