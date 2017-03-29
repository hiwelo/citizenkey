<?php

namespace CitizenKey\WebBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use CitizenKey\CoreBundle\Entity\User;
use CitizenKey\CoreBundle\Entity\Subscription;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = function (FormEvent $event) use ($options) {
            $form = $event->getData();
            $em = $options['em'];
            $platform = $options['platform'];

            // we search if the user already exists
            $searchedEmail = $form['email'];
            $user = $em->getRepository('CoreBundle:User')->findOneByEmail($searchedEmail);

            // if there's no user, we have to create it
            if (!$user instanceof User) {
                // call the method to create a new user here
            }

            // we search if a subscription already exists
            $subscription = $em->getRepository('CoreBundle:Subscription')->findOneBy([
                'user' => $user,
                'platform' => $platform,
            ]);

            // if there's no user, we have to create it
            if (!$subscription instanceof Subscription) {
                // call the method to create a new subscription here
            }
        };

        $builder
            ->add('email', EmailType::class, ['mapped' => false])
            ->add('username', EntityType::class, [
                'class' => 'CoreBundle:User',
                'choice_label' => 'username',
            ])
            ->add('create', SubmitType::class, ['label' => 'Add a new user'])
            ->addEventListener(FormEvents::PRE_SUBMIT, $user);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class,
        ));
        $resolver->setRequired('em');
        $resolver->setRequired('platform');
    }
}
