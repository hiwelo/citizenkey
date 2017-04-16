<?php

namespace CitizenKey\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use CitizenKey\WebBundle\Form\EmailAddressType;
use CitizenKey\CoreBundle\Entity\EmailAddress;

class EmailAddressController extends Controller
{
    /**
     * Creates a new email entry for a contact
     *
     * @Route("/contact/{contact}/email/new/", name="app_email_new")
     *
     * @param string  $contact Contact ID
     * @param Request $request Request object
     *
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function newAction($contact, Request $request)
    {
        $this->denyAccessUnlessGranted('PLATFORM_USER');

        $em = $this->getDoctrine()->getManager();
        $platforms = $em->getRepository('CoreBundle:Platform');
        $people = $em->getRepository('CoreBundle:Person');

        $platform = $platforms->find($this->get('session')->get('platform'));
        $person = $people->findOneBy([
            'platform' => $platform,
            'id' => $contact,
        ]);

        $email = new EmailAddress();
        $email->setPerson($person);

        $form = $this->createForm(EmailAddressType::class, $email);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->getData();
            $em->persist($email);
            $em->flush();

            return $this->redirectToRoute('app_contact', ['contact' => $person->getID()]);
        }

        return $this->render('WebBundle:EmailAddress:new.html.twig', [
            'contact' => $person,
            'form' => $form->createView(),
        ]);
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
}
