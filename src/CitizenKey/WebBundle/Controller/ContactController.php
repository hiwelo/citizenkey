<?php

namespace CitizenKey\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use CitizenKey\WebBundle\Form\ContactType;
use CitizenKey\CoreBundle\Entity\Person;

class ContactController extends Controller
{
    /**
     * Contacts module dashboard
     *
     * @Route("/contacts", name="app_contacts")
     *
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function dashboardAction()
    {
        $em = $this->getDoctrine()->getManager();

        return $this->render('WebBundle:Contact:dashboard.html.twig', array(
            'user' => $this->getUser(),
        ));
    }

    /**
     * Creates a new contact card
     *
     * @Route("/contacts/new", name="app_contact_new")
     *
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $platforms = $em->getRepository('CoreBundle:Platform');
        $platform = $platforms->find($this->get('session')->get('platform'));

        $person = new Person();
        $person->setPlatform($platform);
        $person->setCreationDate(new \DateTime());

        $form = $this->createForm(ContactType::class, $person);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $person = $form->getData();
            $em->persist($person);
            $em->flush();

            return $this->redirectToRoute('app_contact', ['contact' => $person->getID()]);
        }

        return $this->render('WebBundle:Contact:new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Contact card with all informations and details
     *
     * @Route("/contact/{contact}", name="app_contact")
     *
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function cardAction($contact)
    {
        $em = $this->getDoctrine()->getManager();
        $people = $em->getRepository('CoreBundle:Person');

        return $this->render('WebBundle:Contact:card.html.twig', array(
            'user' => $this->getUser(),
            'contact' => $people->find($contact),
        ));
    }

    /**
     * Last created contacts component
     *
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function lastContactsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $people = $em->getRepository('CoreBundle:Person');

        $lastContacts = $people->findBy(
            ['platform' => $this->get('session')->get('platform')],
            ['id' => 'DESC'],
            10
        );

        return $this->render('WebBundle:Contact:lastContacts.html.twig', array(
            'lastContacts' => $lastContacts,
        ));
    }
}
