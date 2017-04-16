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
     * @param Request $request Request object
     *
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $this->denyAccessUnlessGranted('PLATFORM_MANAGER');

        return $this->saveCard(null, $request);
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
     * @param string $contactID Contact ID
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
     * @Route("/contact/{contactID}/edit", name="app_contact_edit")
     *
     * @param string                                   $contact Contact ID
     * @param Symfony\Component\HttpFoundation\Request $request Request object
     *
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function editAction($contactID, Request $request)
    {
        $this->denyAccessUnlessGranted('PLATFORM_MANAGER');

        return $this->saveCard($contactID, $request);
    }

    /**
     * Deletes a specific contact card
     *
     * @Route("/contact/{contact}/remove", name="app_contact_delete")
     *
     * @param integer $contactID ID of the contact card to delete
     *
     * @return Symfony/Component/HttpFoundation/Response
     */
    public function removeAction($contactID)
    {
        $this->denyAccessUnlessGranted('PLATFORM_MANAGER');

        try {
            $this->get('citizenkey.person')->remove($contactID);
        } catch (NotFoundHttpException $e) {
            return $this->redirectToRoute('app_contacts');
        }

        return $this->redirectToRoute('app_contacts');
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
            'card' => $card,
        ]);
    }

    /**
     * Creates or updates informations for an asked person
     *
     * @param integer|null $contactID if non null, the ID of the asked person
     * @param Request $request Symfony HTTP Request object
     *
     * @return Symfony\Component\HttpFoundation\Response
     */
    private function saveCard($contactID, Request $request)
    {
        try {
            $card = $this->get('citizenkey.person')->save($contactID, $request);
        } catch (NotFoundHttpException $e) {
            return $this->redirectToRoute('app_contacts');
        }

        if ($card instanceof Person) {
            return $this->redirectToRoute('app_contact', [
                'contactID' => $card->getID(),
            ]);
        }

        if (is_array($address)) {
            if (is_null($contactID)) {
                $page = 'new';
            } else {
                $page = 'edit';
            }

            return $this->render('WebBundle:Contact:'.$page.'.html.twig', [
                'contact' => $card['person'],
                'form' => $card['form']->createView(),
            ]);
        }
    }
}
