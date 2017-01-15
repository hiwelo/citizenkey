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
        return $this->render('WebBundle:Dashboard:index.html.twig', array(
            // ...
        ));
    }

}
