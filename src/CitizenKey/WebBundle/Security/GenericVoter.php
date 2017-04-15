<?php

namespace CitizenKey\WebBundle\Security;

use CitizenKey\CoreBundle\Entity\User;
use CitizenKey\CoreBundle\Entity\Subscription;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;

class GenericVoter extends Voter
{
    const SUPPORTED = [
        'PLATFORM_USER',
        'PLATFORM_MANAGER',
        'PLATFORM_ADMIN',
        'PLATFORM_OWNER',
    ];

    /**
     * Doctrine Entity Manager
     * @var EntityManager
     */
    private $em;

    /**
     * Session storage
     * @var Session
     */
    private $session;

    /**
     * Decision Manager
     * @var AccessDecisionManagerInterface
     */
    private $decisionManager;

    /**
     * Voter constructor
     *
     * @param EntityManager $em Doctrine Entity Manager
     *
     * @return void
     */
    public function __construct(EntityManager $em, Session $session, AccessDecisionManagerInterface $decisionManager)
    {
        $this->em = $em;
        $this->session = $session;
        $this->decisionManager = $decisionManager;
    }

    /**
     * {@inheritdoc}
     */
    protected function supports($attribute, $subject = null)
    {
        // if the asked role isn't one we support, return false
        if (!in_array($attribute, self::SUPPORTED)) {
            return false;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    protected function voteOnAttribute($attribute, $subject = null, TokenInterface $token)
    {
        // the user must be logged in; if not, deny access
        $user = $token->getUser();
        if (!$user instanceof User) {
            return false;
        }

        $platform = $this->em->getRepository('CoreBundle:Platform')->find($this->session->get('platform'));
        $subscription = $this->em->getRepository('CoreBundle:Subscription')->findOneBy([
            'platform' => $platform,
            'user' => $user,
        ]);

        // the subscription must be existing; if not, deny access
        if (!$subscription instanceof Subscription) {
            return false;
        }

        // if the user is in the staff, he can do anything
        if ($this->decisionManager->decide($token, array('ROLE_STAFF'))) {
            return true;
        }

        // if the user is the owner of the platform, he can do anything he wants to
        if ($platform->getOwner() === $user) {
            return true;
        }

        // if the user have an authorized role, he can see the page
        if (in_array($attribute, $subscription->getRoles())) {
            return true;
        }

        return false;
    }
}
