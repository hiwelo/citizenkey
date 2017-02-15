<?php

namespace CitizenKey\WebBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     *
     * @return void
     */
    public function indexAction()
    {
        return $this->render('WebBundle:Default:index.html.twig');
    }

    /**
     * @Route("/login", name="app_login")
     *
     * @param  Request $request
     * @return void
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
}
