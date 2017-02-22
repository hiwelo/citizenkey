<?php

namespace CitizenKey\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use CitizenKey\CoreBundle\Entity\Phone;

class LoadPhoneData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Fixture loading method
     *
     * @param  ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager)
    {
        $phone = new Phone();
        $phone->setNumber('0601020304');
        $phone->setAllowSMS(false);
        $phone->setAllowCampaign(false);
        $phone->setPerson($this->getReference('person'));

        $manager->persist($phone);
        $manager->flush();

        $this->setReference('phone', $phone);
    }

    /**
     * Get fixture loading order
     *
     * @return integer Order
     */
    public function getOrder()
    {
        return 5;
    }
}
