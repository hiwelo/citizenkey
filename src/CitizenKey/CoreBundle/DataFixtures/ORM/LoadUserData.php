<?php

namespace CitizenKey\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use CitizenKey\CoreBundle\Entity\User;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Fixture loading method
     *
     * @param  ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager)
    {
        $userAdmin = new User();
        $userAdmin->setUsername('admin');
        $userAdmin->setNewPassword('test');
        $userAdmin->setEmail('damien@raccoon.studio');
        $userAdmin->setActivation(true);
        $userAdmin->setRoles(['ROLE_USER', 'ROLE_MANAGER', 'ROLE_TECH', 'ROLE_ADMIN']);

        $manager->persist($userAdmin);
        $manager->flush();

        $this->addReference('user', $userAdmin);
    }

    /**
     * Get fixture loading order
     *
     * @return integer Order
     */
    public function getOrder()
    {
        return 1;
    }
}
