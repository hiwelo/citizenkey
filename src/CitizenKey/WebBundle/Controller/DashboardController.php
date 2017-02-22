<?php

namespace CitizenKey\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DashboardController extends Controller
{
    /**
     * @Route("/dashboard", name="app_dashboard")
     *
     * @return void
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
     * @Route("/dashboard/mailtest", name="app_dashboard_mail")
     *
     * @return void
     */
    public function mailAction()
    {
        $message = \Swift_Message::newInstance()
            ->setSubject('Test mail')
            ->setFrom('damien@raccoon.studio')
            ->setTo('mail@damiensenger.me')
            ->setBody($this->renderView('WebBundle:Emails:test.html.twig'), 'text/html');

        $this->get('mailer')->send($message);
        return $this->render('WebBundle:Dashboard:mailtest.html.twig');
    }

    /**
     * Check if the user have access to the platform
     *
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
