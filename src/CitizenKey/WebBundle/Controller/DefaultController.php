<?php

namespace CitizenKey\WebBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * Load the header bar component into the template
     *
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function headerAction()
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        $platformID = $this->get('session')->get('platform');
        $platform = $em->getRepository('CoreBundle:Platform')->find($platformID);
        $subscription = $em->getRepository('CoreBundle:Subscription')->findOneBy([
            'platform' => $platform,
            'user' => $user,
        ]);

        if (null === $platform || null === $subscription) {
            return $this->redirectToRoute('app_platform_choice');
        }

        return $this->render('WebBundle:Default:header.html.twig', [
            'user' => $user,
            'subscription' => $subscription,
        ]);
    }

    /**
     * @Route("/login", name="app_login")
     *
     * @param  Request $request
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function loginAction(Request $request)
    {
        $helper = $this->get('security.authentication_utils');

        return $this->render('WebBundle:Default:login.html.twig', [
            'error' => $helper->getLastAuthenticationError(),
        ]);
    }

    /**
     * @Route("/logout", name="app_logout")
     *
     * @param  Request $request
     * @return void
     */
    public function logoutAction(Request $request)
    {
    }

    /**
     * @Route("/login_check", name="app_login_check")
     *
     * @param  Request $request
     * @return void
     */
    public function loginCheckAction(Request $request)
    {
    }

    /**
     * Load the Skip Links component into the template
     *
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function skipLinksAction()
    {
        return $this->render('WebBundle:Default:SkipLinks.html.twig');
    }
}
