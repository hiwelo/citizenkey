<?php

namespace CitizenKey\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PhoneController extends Controller
{
    /**
     * Creates a new contact card
     *
     * @Route("/contact/{contact}/phone/new/", name="app_phone_new")
     *
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function newAction($contact, Request $request)
    {
        return $this->render('WebBundle:Contact:new.html.twig', []);
    }

    /**
     * Returns a component with all phone numbers for an asked contact
     *
     * @param string $contact Asked contact ID
     *
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function contactEntriesAction($contact)
    {
        $em = $this->getDoctrine()->getManager();

        $platforms = $em->getRepository('CoreBundle:Platform');
        $platform = $platforms->find($this->get('session')->get('platform'));

        $people = $em->getRepository('CoreBundle:Person');

        $person = $people->findOneBy([
            'id' => $contact,
            'platform' => $platform,
        ]);

        if (!$person) {
            return false;
        }

        $phones = $em->getRepository('CoreBundle:Phone')->findBy(
            ['person' => $person],
            ['id' => 'ASC']
        );

        return $this->render('WebBundle:Phone:ContactEntries.html.twig', [
            'contact' => $person,
            'phones' => $phones,
        ]);
    }
}
