<?php

namespace CitizenKey\WebBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use CitizenKey\CoreBundle\Entity\Person;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class)
            ->add('lastname', TextType::class)
            ->add('gender', ChoiceType::class, [
                'choices' => [
                    'Male' => 1,
                    'Female' => 2,
                    'Other' => 3,
                    'Unknown' => null,
                ],
                'data' => null,
            ])
            ->add('birthdate', DateType::class, [
                'years' => range(1900, date('Y')),
            ])
            ->add('voter', ChoiceType::class, [
                'choices' => [
                    'Registered' => true,
                    'Unregistered' => false,
                ],
                'expanded' => true,
                'multiple' => false,
                'data' => false,
            ])
            ->add('create', SubmitType::class, ['label' => 'Create new contact'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Person::class,
        ));
    }
}
