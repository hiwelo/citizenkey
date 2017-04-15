<?php

namespace CitizenKey\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use CitizenKey\WebBundle\Form\ContactType;
use CitizenKey\CoreBundle\Entity\Person;
use CitizenKey\CoreBundle\Factory\PersonFactory;

class ContactController extends Controller
{
    /**
     * Contacts module dashboard
     *
     * @Route("/contacts/", name="app_contacts")
     *
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function dashboardAction()
    {
        $this->denyAccessUnlessGranted('PLATFORM_MANAGER');

        $em = $this->getDoctrine()->getManager();

        return $this->render('WebBundle:Contact:dashboard.html.twig', []);
    }

    /**
     * Creates a new contact card
     *
     * @Route("/contacts/new/", name="app_contact_new")
     *
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $this->denyAccessUnlessGranted('PLATFORM_MANAGER');

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
     * Searches a person and returns all found cards
     *
     * @Route("/contacts/search/", name="app_contacts_search")
     *
     * @param Request $request User request
     *
     * @return Symfony/Component/HttpFoundation/Response
     */
    public function searchAction(Request $request)
    {
        $criteria = $request->query->all() + $request->request->all();

        $cards = $this->get('citizenkey.person')->search($criteria);

        return $this->render('WebBundle:Contact:search.html.twig', [
            'cards' => $cards,
        ]);
    }

    /**
     * Contact card with all informations and details
     *
     * @Route("/contact/{contactID}/", name="app_contact")
     *
     * @param string $contact Contact ID
     *
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function cardAction($contactID)
    {
        $this->denyAccessUnlessGranted('PLATFORM_MANAGER');

        try {
            $contact = $this->get('citizenkey.person')->load($contactID);
        } catch (NotFoundHttpException $e) {
            return $this->redirectToRoute('app_contacts');
        }

        return $this->render('WebBundle:Contact:card.html.twig', [
            'user' => $this->getUser(),
            'contact' => $contact,
        ]);
    }

    /**
     * Contact card edition
     *
     * @Route("/contact/{contact}/edit", name="app_contact_edit")
     *
     * @param string                                   $contact Contact ID
     * @param Symfony\Component\HttpFoundation\Request $request Request object
     *
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function editAction($contact, Request $request)
    {
        $this->denyAccessUnlessGranted('PLATFORM_MANAGER');

        $em = $this->getDoctrine()->getManager();

        $platforms = $em->getRepository('CoreBundle:Platform');
        $platform = $platforms->find($this->get('session')->get('platform'));

        $people = $em->getRepository('CoreBundle:Person');

        $person = $people->findOneBy([
            'id' => $contact,
            'platform' => $platform,
        ]);

        if (!$person) {
            return $this->redirectToRoute('app_contacts');
        }

        $form = $this->createForm(ContactType::class, $person);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $person = $form->getData();
            $em->persist($person);
            $em->flush();

            return $this->redirectToRoute('app_contact', ['contact' => $person->getID()]);
        }

        return $this->render('WebBundle:Contact:edit.html.twig', [
            'user' => $this->getUser(),
            'contact' => $person,
            'form' => $form->createView(),
        ]);
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

    /**
     * Returns the template for the small summary card for a contact
     *
     * @param Person $card Person entity
     *
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function smallCardAction($card)
    {
        return $this->render('WebBundle:Contact:smallCard.html.twig', [
            'card' => $card
        ]);
    }
}
