<?php

namespace CitizenKey\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use CitizenKey\WebBundle\Form\EmailAddressType;
use CitizenKey\CoreBundle\Entity\EmailAddress;

class EmailAddressController extends Controller
{
    /**
     * Creates a new email entry for a contact
     *
     * @Route("/contact/{contact}/email/new/", name="app_email_new")
     *
     * @param string  $contactID Contact ID
     * @param Request $request   Request object
     *
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function newAction($contactID, Request $request)
    {
        $this->denyAccessUnlessGranted('PLATFORM_USER');

        return $this->saveEntry(null, $contactID, $request);
    }

    /**
     * Updates an existing email entry for an asked contact
     *
     * @Route("/contact/{contact}/email/{emailID}/edit", name="app_email_new")
     *
     * @param integer $contactID ID of the contact related to this email entry
     * @param integer $emailID   ID of the email entry
     * @param Request $request   Symfony HTTP Request object
     *
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function editAction($contactID, $emailID, Request $request)
    {
        $this->denyAccessUnlessGranted('PLATFORM_MANAGER');

        return $this->saveEntry($emailID, $contactID, $request);
    }

    /**
     * Returns a component with all emails for an asked contact
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

        $emails = $em->getRepository('CoreBundle:EmailAddress')->findBy(
            ['person' => $person],
            ['id' => 'ASC']
        );

        return $this->render('WebBundle:EmailAddress:ContactEntries.html.twig', [
            'contact' => $person,
            'emails' => $emails,
        ]);
    }

    /**
     * Creates or updates an email address entry with the asked informations
     *
     * @param integer|null $emailID   ID of the asked Email address, or null
     * @param integer      $contactID ID of the related contact card
     * @param Request      $request   Symfony HTTP request object
     *
     * @return Symfony\Component\HttpFoundation\Response
     */
    private function saveEntry($emailID, $contactID, Request $request)
    {
        try {
            $email = $this->get('citizenkey.emailaddress')->save($emailID, $contactID, $request);
        } catch (NotFoundHttpException $e) {
            return $this->redirectToRoute('app_contacts');
        }

        if ($email instanceof EmailAddress) {
            return $this->redirectToRoute('app_contact', [
                'contactID' => $email->getPerson()->getID(),
            ]);
        }

        if (is_array($email)) {
            if (is_null($emailID)) {
                $page = 'new';
            } else {
                $page = 'edit';
            }

            return $this->render('WebBundle:Email:'.$page.'.html.twig', [
                'contact' => $email['email']->getPerson(),
                'form' => $email['form']->createView(),
            ]);
        }
    }
}
