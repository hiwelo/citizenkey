<?php

namespace CitizenKey\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class GeographyController extends Controller
{
    /**
     * @Route("/geography/", name="app_geography")
     *
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $this->denyAccessUnlessGranted('PLATFORM_ADMIN');

        return $this->render('WebBundle:Geography:dashboard.html.twig', []);
    }

    /**
     * Searches cartographic elements
     *
     * @Route("/geography/search", name="app_geography_search")
     *
     * @param  Request $request
     *
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function searchAction(Request $request)
    {
        $this->denyAccessUnlessGranted('PLATFORM_ADMIN');
        $em = $this->getDoctrine()->getManager();

        $search = $request->query->get('q');

        return $this->render('WebBundle:Geography:search.html.twig', [
            'search' => $search,
        ]);
    }
}
