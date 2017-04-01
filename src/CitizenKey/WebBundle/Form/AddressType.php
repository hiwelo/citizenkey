<?php

namespace CitizenKey\WebBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use CitizenKey\CoreBundle\Entity\Address;
use CitizenKey\CoreBundle\Entity\AdminLevel;
use CitizenKey\CoreBundle\Entity\Country;
use CitizenKey\CoreBundle\Entity\ZipCode;
use CitizenKey\CoreBundle\Entity\City;
use CitizenKey\CoreBundle\Entity\Street;
use CitizenKey\CoreBundle\Entity\StreetNumber;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $geocoding = function (FormEvent $event) use ($options) {
            $form = $event->getData();
            $geocoder = $options['geocoder'];
            $em = $options['em'];

            $address = $geocoder->geocode($form['address'])->first();

            $form['longitude'] = $address->getLongitude();
            $form['latitude'] = $address->getLatitude();

            // We search if the country exists, if not we create it
            $country = $em->getRepository('CoreBundle:Country')->findOneBy(
                ['code' => $address->getCountry()->getCode()]
            );

            if (is_null($country)) {
                $country = new Country();
                $country->setCode($address->getCountry()->getCode());
                $country->setName($address->getCountry()->getName());
                $em->persist($country);
                $em->flush();
            }
            $form['country'] = $country->getID();

            // We search if the zipcode exists, if not we create it
            $zipcode = $em->getRepository('CoreBundle:ZipCode')->findOneBy(
                [
                    'zipcode' => $address->getPostalCode(),
                    'country' => $country,
                ]
            );

            if (is_null($zipcode)) {
                $zipcode = new ZipCode();
                $zipcode->setZipcode($address->getPostalCode());
                $zipcode->setCountry($country);
                $em->persist($zipcode);
                $em->flush();
            }
            $form['zipcode'] = $zipcode->getID();

            // We search if the administrative level exists, if not we create it
            $levels = $address->getAdminLevels();
            foreach ($levels as $key => $level) {
                $adminLevel = $em->getRepository('CoreBundle:AdminLevel')->findOneBy(
                    [
                        'code' => $level->getCode(),
                        'country' => $country,
                    ]
                );

                if (is_null($adminLevel)) {
                    $adminLevel = new AdminLevel();
                    $adminLevel->setLevel($level->getLevel());
                    $adminLevel->setName($level->getName());
                    $adminLevel->setCode($level->getCode());
                    $adminLevel->setCountry($country);
                    $em->persist($adminLevel);
                    $em->flush();
                }
                $form['adminLevel'.$key] = $adminLevel->getID();
            }

            // We look if we have a locality and/or a sublocality,
            // and we choose one of this two
            if (!$address->getLocality() && $address->getSubLocality()) {
                $localityName = $address->getSubLocality();
            } else {
                $localityName = $address->getLocality();
            }

            // We search if the city exists, if not we create it
            $city = $em->getRepository('CoreBundle:City')->findOneBy(
                [
                    'name' => $localityName,
                    'country' => $country,
                ]
            );

            if (is_null($city)) {
                $city = new City();
                $city->setName($localityName);
                $city->setCountry($country);
                $em->persist($city);
                $em->flush();
            }
            $form['city'] = $city->getID();

            // We search if the street exists, if not we create it
            $street = $em->getRepository('CoreBundle:Street')->findOneBy(
                [
                    'name' => $address->getStreetName(),
                    'city' => $city,
                ]
            );

            if (is_null($street)) {
                $street = new Street();
                $street->setName($address->getStreetName());
                $street->setCity($city);
                $em->persist($street);
                $em->flush();
            }
            $form['street'] = $street->getID();

            // We search if the street number exists, if not we create it
            $streetNumber = $em->getRepository('CoreBundle:StreetNumber')->findOneBy(
                [
                    'number' => $address->getStreetNumber(),
                    'street' => $street,
                ]
            );

            if (is_null($streetNumber)) {
                $streetNumber = new StreetNumber();
                $streetNumber->setNumber($address->getStreetNumber());
                $streetNumber->setStreet($street);
                $em->persist($streetNumber);
                $em->flush();
            }
            $form['streetNumber'] = $streetNumber->getID();

            $event->setData($form);

            return $event;
        };

        $builder
            ->add('address', TextType::class, ['mapped' => false])
            ->add('latitude', HiddenType::class)
            ->add('longitude', HiddenType::class)
            ->add('country', EntityType::class, [
                'class' => 'CoreBundle:Country',
                'choice_label' => 'name',
            ])
            ->add('zipcode', EntityType::class, [
                'class' => 'CoreBundle:ZipCode',
                'choice_label' => 'zipcode',
            ])
            ->add('city', EntityType::class, [
                'class' => 'CoreBundle:City',
                'choice_label' => 'name',
            ])
            ->add('adminLevel1', EntityType::class, [
                'class' => 'CoreBundle:AdminLevel',
                'choice_label' => 'name',
            ])
            ->add('adminLevel2', EntityType::class, [
                'class' => 'CoreBundle:AdminLevel',
                'choice_label' => 'name',
            ])
            ->add('street', EntityType::class, [
                'class' => 'CoreBundle:Street',
                'choice_label' => 'name',
            ])
            ->add('streetNumber', EntityType::class, [
                'class' => 'CoreBundle:StreetNumber',
                'choice_label' => 'number',
            ])
            ->add('create', SubmitType::class, ['label' => 'Add new'])
            ->addEventListener(FormEvents::PRE_SUBMIT, $geocoding);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Address::class,
        ));
        $resolver->setRequired('geocoder');
        $resolver->setRequired('em');
    }
}
