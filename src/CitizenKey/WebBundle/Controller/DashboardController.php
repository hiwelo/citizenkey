<?php

namespace CitizenKey\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DashboardController extends Controller
{
    /**
     * @Route("/dashboard", name="app_dashboard")
     */
    public function indexAction()
    {
        $this->subscriptionCheck();

        $em = $this->getDoctrine()->getManager();
        $platforms = $em->getRepository('CoreBundle:Platform');
        $platform = $platforms->find($this->userSubscription->getPlatform());

        return $this->render('WebBundle:Dashboard:index.html.twig', array(
            'platform' => $platform,
        ));
    }

    /**
     * Check if the user have access to the platform
     * @return boolean
     */
    private function subscriptionCheck()
    {
        $selectedPlatform = $this->get('session')->get('platform');
        $subscriptions = $this->getDoctrine()->getManager()->getRepository('CoreBundle:Subscription');
        $subscription = $subscriptions->findOneBy([
            'user' => $this->getUser(),
            'platform' => $selectedPlatform,
            'active' => true,
        ]);

        if (null === $subscription) {
            return $this->redirectToRoute('app_platform_choice');
        }

        $this->userSubscription = $subscription;

        return true;
    }
}
