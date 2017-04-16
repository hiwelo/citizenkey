<?php

namespace CitizenKey\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use CitizenKey\WebBundle\Form\PhoneType;
use CitizenKey\CoreBundle\Entity\Phone;

class PhoneController extends Controller
{
    /**
     * Creates a new phone entry for a contact
     *
     * @Route("/contact/{contact}/phone/new/", name="app_phone_new")
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

        $phone = new Phone();
        $phone->setPerson($person);

        $form = $this->createForm(PhoneType::class, $phone);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $phone = $form->getData();
            $em->persist($phone);
            $em->flush();

            return $this->redirectToRoute('app_contact', ['contact' => $person->getID()]);
        }

        return $this->render('WebBundle:Phone:new.html.twig', [
            'contact' => $person,
            'form' => $form->createView(),
        ]);
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
