<?php

namespace CitizenKey\WebBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
        try {
            $subscription = $this->get('citizenkey.subscription')->getCurrent();
        } catch (NotFoundHttpException $e) {
            return $this->redirectToRoute('app_platform_choice');
        }

        return $this->render('WebBundle:Default:header.html.twig', [
            'user' => $this->getUser(),
            'subscription' => $subscription,
        ]);
    }

    /**
     * Load the footer bar component into the template
     *
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function footerAction()
    {
        return $this->render('WebBundle:Default:footer.html.twig', []);
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
