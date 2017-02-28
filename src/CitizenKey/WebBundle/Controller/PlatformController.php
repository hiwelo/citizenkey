<?php

namespace CitizenKey\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class PlatformController extends Controller
{
    /**
     * @Route("/platform/choice", name="app_platform_choice")
     * @return void
     */
    public function choiceAction()
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $subs = $em->getRepository('CoreBundle:Subscription');
        $subs = $subs->findByUser($user);

        if (null === $subs) {
            return $this->redirectToRoute('app_login');
        }

        if (1 === count($subs)) {
            return $this->redirectToRoute('app_platform_select', ['platform' => $subs[0]->getId()]);
        }

        return $this->render('WebBundle:Platform:select.html.twig', []);
    }

    /**
     * @Route("/platform", name="app_platform_dashboard")
     *
     * @return void
     */
    public function dashboardAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $platformID = $this->get('session')->get('platform');
        $platform = $em->getRepository('CoreBundle:Platform')->find($platformID);
        $subscription = $em->getRepository('CoreBundle:Subscription')->findOneBy([
            'platform' => $platform,
            'user' => $user,
        ]);

        if (null === $platform) {
            return $this->redirectToRoute('app_platform_choice');
        }

        return $this->render('WebBundle:Platform:Dashboard.html.twig', [
            'user' => $user,
            'platform' => $platform,
            'subscription' => $subscription,
        ]);
    }

    /**
     * @Route("/platform/select/{platform}", name="app_platform_select")
     *
     * @param  string $platform platform id
     * @return void
     */
    public function selectAction($platform = null)
    {
        if (null === $platform) {
            return $this->redirectToRoute('app_platform_choice');
        }

        $em = $this->getDoctrine()->getManager();
        $subs = $em->getRepository('CoreBundle:Subscription');
        $subscription = $subs->find($platform);

        if ($subscription->getUser() === $this->getUser()) {
            $this->get('session')->set('platform', $subscription->getPlatform()->getID());

            return $this->redirectToRoute('app_dashboard');
        } else {
            return $this->redirectToRoute('app_platform_choice');
        }
    }
}
