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

        return $this->saveAddress($contact, null, $request);
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
        $this->denyAccessUnlessGranted('PLATFORM_MANAGER');

        return $this->saveAddress($contactID, $addressID, $request);
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

        return $this->redirectToRoute('app_contact', ['contactID' => $contactID]);
    }

    /**
     * Returns a component with all addresses for an asked contact
     *
     * @param string $contactID Asked contact ID
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

    /**
     * Returns block address template
     *
     * @param string $addressID Asked address ID
     *
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function getBlockAddressAction($addressID)
    {
        $em = $this->getDoctrine()->getManager();
        $address = $em->getRepository('CoreBundle:Address')
            ->find($addressID);

        try {
            $formattedAddress = $this->get('citizenkey.address')
                ->formatAddress($address, true, false);
        } catch (NotFoundHttpException $e) {
            return $this->redirectToRoute('app_contacts');
        }

        return $this->render('WebBundle:Address:address.html.twig', [
            'address' => $address,
            'formattedAddress' => $formattedAddress,
        ]);
    }

    /**
     * Returns inline address template
     *
     * @param string $addressID Asked address ID
     *
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function getInlineAddressAction($addressID)
    {
        $em = $this->getDoctrine()->getManager();
        $address = $em->getRepository('CoreBundle:Address')
            ->find($addressID);

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

    /**
     * Creates or updates an address entry with the asked informations
     *
     * @param string  $contactID Contact ID
     * @param string  $addressID Address entry ID, null for a creation
     * @param Request $request   Request object
     *
     * @return Symfony\Component\HttpFoundation\Response
     */
    private function saveAddress($contactID, $addressID, Request $request)
    {
        try {
            $address = $this->get('citizenkey.address')->save($contactID, $addressID, $request);
        } catch (NotFoundHttpException $e) {
            return $this->redirectToRoute('app_contacts');
        }

        if ($address instanceof Address) {
            return $this->redirectToRoute('app_contact', [
                'contactID' => $address->getPerson()->getID(),
            ]);
        }

        if (is_array($address)) {
            return $this->render('WebBundle:Address:new.html.twig', [
                'contact' => $address['address']->getPerson(),
                'form' => $address['form']->createView(),
            ]);
        }
    }
}
