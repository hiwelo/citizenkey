<?php

namespace CitizenKey\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use CitizenKey\CoreBundle\Entity\Subscription;

class LoadSubscriptionData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Fixture loading method
     *
     * @param  ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager)
    {
        $subscription = new Subscription();
        $subscription->setPlatform($this->getReference('platform'));
        $subscription->setUser($this->getReference('user'));
        $subscription->setActive(true);
        $subscription->setRoles(['PLATFORM_ADMIN', 'PLATFORM_MANAGER', 'PLATFORM_USER']);

        $manager->persist($subscription);

        $subscription2 = new Subscription();
        $subscription2->setPlatform($this->getReference('platform'));
        $subscription2->setUser($this->getReference('userTest'));
        $subscription2->setActive(true);
        $subscription2->setRoles(['PLATFORM_USER']);

        $manager->persist($subscription2);
        $manager->flush();

        $this->setReference('subscription', $subscription);
    }

    /**
     * Get fixture loading order
     *
     * @return integer Order
     */
    public function getOrder()
    {
        return 3;
    }
}
