<?php

namespace CitizenKey\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use CitizenKey\CoreBundle\Entity\User;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $userAdmin = new User();
        $userAdmin->setUsername('admin');
        $userAdmin->setNewPassword('test');
        $userAdmin->setEmail('test@domain.net');
        $userAdmin->setActivation(true);

        $manager->persist($userAdmin);
        $manager->flush();

        $this->addReference('user', $userAdmin);
    }

    public function getOrder()
    {
        return 1;
    }
}
