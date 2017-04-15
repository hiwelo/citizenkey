<?php

namespace CitizenKey\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use CitizenKey\WebBundle\Form\AddressType;
use CitizenKey\CoreBundle\Entity\Address;

class AddressController extends Controller
{
    /**
     * Creates a new address entry for a contact
     *
     * @Route("/contact/{contact}/address/new/", name="app_address_new")
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

        $address = new Address();
        $address->setPerson($person);

        $form = $this->createForm(AddressType::class, $address, [
            'geocoder' => $this->container->get('bazinga_geocoder.geocoder'),
            'em' => $this->getDoctrine()->getManager(),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $address = $form->getData();
            $em->persist($address);
            $em->flush();

            return $this->redirectToRoute('app_contact', ['contact' => $person->getID()]);
        }

        return $this->render('WebBundle:Address:new.html.twig', [
            'contact' => $person,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Edits a specific address entry for the asked contact
     *
     * @Route("/contact/{contactID}/address/{addressID}/edit/", name="app_address_edit")
     *
     * @param string  $contactID Contact ID
     * @param string  $addressID Address entry ID
     * @param Request $request   Request object
     *
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function editAction($contactID, $addressID, Request $request)
    {
        $this->denyAccessUnlessGranted('PLATFORM_USER');

        $em = $this->getDoctrine()->getManager();
        $address = $em->getRepository('CoreBundle:Address')->find($addressID);

        if (!$address instanceof Address) {
            return $this->redirectToRoute('app_contacts');
        }

        if ($address->getPerson()->getID() != $contactID) {
            return $this->redirectToRoute('app_contact', ['contact' => $contactID]);
        }

        $form = $this->createForm(AddressType::class, $address, [
            'geocoder' => $this->container->get('bazinga_geocoder.geocoder'),
            'em' => $this->getDoctrine()->getManager(),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $address = $form->getData();
            $em->persist($address);
            $em->flush();

            return $this->redirectToRoute('app_contact', ['contact' => $person->getID()]);
        }

        return $this->render('WebBundle:Address:edit.html.twig', [
            'contact' => $address->getPerson(),
            'form' => $form->createView(),
        ]);
    }

    /**
     * Removes a specific address entry for the asked contact
     *
     * @Route("/contact/{contactID}/address/{addressID}/remove/", name="app_address_remove")
     *
     * @param string  $contactID Contact ID
     * @param string  $addressID Address entry ID
     * @param Request $request   Request object
     *
     * @return void
     */
    public function removeAction($contactID, $addressID, Request $request)
    {
        $this->denyAccessUnlessGranted('PLATFORM_MANAGER');

        try {
            $this->get('citizenkey.address')->remove($addressID);
        } catch (NotFoundHttpException $e) {
            return $this->redirectToRoute('app_contacts');
        }

        return $this->redirectToRoute('app_contact', ['contact' => $contactID]);
    }

    /**
     * Returns a component with all addresses for an asked contact
     *
     * @param string $contact Asked contact ID
     *
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function contactEntriesAction($contactID)
    {
        try {
            $addresses = $this->get('citizenkey.address')->loadForCard($contactID);
        } catch (NotFoundHttpException $e) {
            return $this->redirectToRoute('app_contacts');
        }

        return $this->render('WebBundle:Address:ContactEntries.html.twig', [
            'contactID' => $contactID,
            'addresses' => $addresses,
        ]);
    }

    public function getInlineAddressAction($address)
    {
        $em = $this->getDoctrine()->getManager();
        $address = $em->getRepository('CoreBundle:Address')
            ->find($address);

        try {
            $formattedAddress = $this->get('citizenkey.address')
                ->formatAddress($address, true, true);
        } catch (NotFoundHttpException $e) {
            return $this->redirectToRoute('app_contacts');
        }

        return $this->render('WebBundle:Address:address.html.twig', [
            'address' => $address,
            'formattedAddress' => $formattedAddress,
        ]);
    }
}
