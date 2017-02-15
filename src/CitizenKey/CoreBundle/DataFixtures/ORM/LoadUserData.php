<?php

namespace CitizenKey\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use CitizenKey\CoreBundle\Entity\User;

class LoadUserData implements FixtureInterface
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
    }
}
