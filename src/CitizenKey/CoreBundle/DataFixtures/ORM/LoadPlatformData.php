<?php

namespace CitizenKey\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use CitizenKey\CoreBundle\Entity\Platform;

class LoadPlatformData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $platform = new Platform();
        $platform->setSlug('test');
        $platform->setName('Test');
        $platform->setOwner($this->getReference('user'));

        $manager->persist($platform);
        $manager->flush();

        $this->setReference('platform', $platform);
    }

    public function getOrder()
    {
        return 2;
    }
}
