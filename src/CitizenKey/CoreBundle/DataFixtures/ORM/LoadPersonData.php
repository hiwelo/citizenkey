<?php

namespace CitizenKey\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use CitizenKey\CoreBundle\Entity\Person;

class LoadPersonData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Fixture loading method
     *
     * @param  ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager)
    {
        $person = new Person();
        $person->setFirstname('Jean');
        $person->setLastname('Dupont');
        $person->setGender('1');
        $person->setVoter(false);
        $person->setBirthdate((new \DateTime('1990-01-30')));
        $person->setPlatform($this->getReference('platform'));
        $person->setCreationDate(new \DateTime());

        $manager->persist($person);
        $manager->flush();

        $this->setReference('person', $person);
    }

    /**
     * Get fixture loading order
     *
     * @return integer Order
     */
    public function getOrder()
    {
        return 4;
    }
}
