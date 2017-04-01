<?php

namespace CitizenKey\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use CitizenKey\WebBundle\Form\AddressType;
use CitizenKey\CoreBundle\Entity\Address;

class AddressController extends Controller
{
    /**
     * Creates a new address entry for a contact
     *
     * @Route("/contact/{contact}/address/new/", name="app_address_new")
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
     * Returns a component with all addresses for an asked contact
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

        $addresses = $em->getRepository('CoreBundle:Address')->findBy(
            ['person' => $person],
            ['id' => 'ASC']
        );

        return $this->render('WebBundle:Address:ContactEntries.html.twig', [
            'contact' => $person,
            'addresses' => $addresses,
        ]);
    }

    public function getInlineAddressAction($address)
    {
        $em = $this->getDoctrine()->getManager();
        $address = $em->getRepository('CoreBundle:Address')->find($address);

        $formatter = $this->get('address.formatter');
        $formattedAddress = $formatter->load([
            'countryCode' => $address->getCountry()->getCode(),
            'countryName' => $address->getCountry()->getName(),
            'regionName' => $address->getAdminLevel1()->getName(),
            'regionCode' => $address->getAdminLevel1()->getCode(),
            'locality' => $address->getCity()->getName(),
            'postalcode' => $address->getZipcode()->getZipcode(),
            'street' => $address->getStreet()->getName(),
            'streetNumber' => $address->getStreetNumber()->getNumber(),
        ]);

        $result = $formatter->format($formattedAddress, [
            'country' => true,
            'inline' => true,
        ]);

        return $this->render('WebBundle:Address:address.html.twig', [
            'address' => $result,
        ]);
    }
}
