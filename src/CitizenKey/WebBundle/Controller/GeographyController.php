<?php

namespace CitizenKey\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class GeographyController extends Controller
{
    /**
     * @Route("/geography/", name="app_geography")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $user = $this->getUser();
        $platformID = $this->get('session')->get('platform');
        $platform = $em->getRepository('CoreBundle:Platform')->find($platformID);
        $subscription = $em->getRepository('CoreBundle:Subscription')->findOneBy([
            'platform' => $platform,
            'user' => $user,
        ]);

        return $this->render('WebBundle:Geography:dashboard.html.twig', array(
            'user' => $user,
            'platform' => $platform,
            'subscription' => $subscription,
        ));
    }
}
