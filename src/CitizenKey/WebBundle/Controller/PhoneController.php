<?php

namespace CitizenKey\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use CitizenKey\WebBundle\Form\PhoneType;
use CitizenKey\CoreBundle\Entity\Phone;

class PhoneController extends Controller
{
    /**
     * Creates a new phone entry for a contact
     *
     * @Route("/contact/{contactID}/phone/new/", name="app_phone_new")
     *
     * @param string  $contact Contact ID
     * @param Request $request Request object
     *
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function newAction($contactID, Request $request)
    {
        return $this->saveEntry(null, $contactID, $request)
    }

    /**
     * Updates an existing phone entry for an asked contact
     *
     * @Route("/contact/{contact}/phone/{phoneID}/edit", name="app_phone_edit")
     *
     * @param integer $contactID ID of the contact related to this email entry
     * @param integer $phoneID   ID of the phone entry
     * @param Request $request   Symfony HTTP Request object
     *
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function editAction($contactID, $phoneID, Request $request)
    {
        return $this->saveEntry($phoneID, $contactID, $request);
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

    /**
     * Creates or updates an phone entry with the asked informations
     *
     * @param integer|null $phoneID   ID of the asked Phone, or null
     * @param integer      $contactID ID of the related contact card
     * @param Request      $request   Symfony HTTP request object
     *
     * @return Symfony\Component\HttpFoundation\Response
     */
    private function saveEntry($phoneID, $contactID, Request $request)
    {
        try {
            $phone = $this->get('citizenkey.phone')->save($phoneID, $contactID, $request);
        } catch (NotFoundHttpException $e) {
            return $this->redirectToRoute('app_contacts');
        }

        if ($phone instanceof EmailAddress) {
            return $this->redirectToRoute('app_contact', [
                'contactID' => $phone->getPerson()->getID(),
            ]);
        }

        if (is_array($phone)) {
            if (is_null($phoneID)) {
                $page = 'new';
            } else {
                $page = 'edit';
            }

            return $this->render('WebBundle:Email:'.$page.'.html.twig', [
                'contact' => $phone['email']->getPerson(),
                'form' => $phone['form']->createView(),
            ]);
        }
    }
}
