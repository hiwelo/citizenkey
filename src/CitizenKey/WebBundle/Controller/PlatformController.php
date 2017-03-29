<?php

namespace CitizenKey\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use CitizenKey\WebBundle\Form\UserType;
use CitizenKey\CoreBundle\Entity\User;

class PlatformController extends Controller
{
    /**
     * @Route("/platform/choice", name="app_platform_choice")
     * @return void
     */
    public function choiceAction()
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $subs = $em->getRepository('CoreBundle:Subscription');
        $subs = $subs->findByUser($user);

        if (null === $subs) {
            return $this->redirectToRoute('app_login');
        }

        if (1 === count($subs)) {
            return $this->redirectToRoute('app_platform_select', ['platform' => $subs[0]->getId()]);
        }

        return $this->render('WebBundle:Platform:select.html.twig', []);
    }

    /**
     * @Route("/platform", name="app_platform_dashboard")
     *
     * @return void
     */
    public function dashboardAction()
    {
        $this->denyAccessUnlessGranted('PLATFORM_ADMIN');

        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $platformID = $this->get('session')->get('platform');
        $platform = $em->getRepository('CoreBundle:Platform')->find($platformID);
        $subscription = $em->getRepository('CoreBundle:Subscription')->findOneBy([
            'platform' => $platform,
            'user' => $user,
        ]);

        if (null === $platform || null === $subscription) {
            return $this->redirectToRoute('app_platform_choice');
        }

        return $this->render('WebBundle:Platform:Dashboard.html.twig', [
            'user' => $user,
            'platform' => $platform,
            'subscription' => $subscription,
        ]);
    }

    /**
     * @Route("/platform/select/{platform}", name="app_platform_select")
     *
     * @param  string $platform platform id
     * @return void
     */
    public function selectAction($platform = null)
    {
        if (null === $platform) {
            return $this->redirectToRoute('app_platform_choice');
        }

        $em = $this->getDoctrine()->getManager();
        $subs = $em->getRepository('CoreBundle:Subscription');
        $subscription = $subs->find($platform);

        if ($subscription->getUser() === $this->getUser()) {
            $this->get('session')->set('platform', $subscription->getPlatform()->getID());

            return $this->redirectToRoute('app_dashboard');
        } else {
            return $this->redirectToRoute('app_platform_choice');
        }
    }

    /**
     * @Route("/platform/user/add", name="app_platform_adduser")
     *
     * @param  string $platform platform id
     * @return void
     */
    public function addUserAction(Request $request)
    {
        $this->denyAccessUnlessGranted('PLATFORM_ADMIN');

        $em = $this->getDoctrine()->getManager();

        $user = new User();

        $form = $this->createForm(UserType::class, $user, [
            'em' => $this->getDoctrine()->getManager(),
            'platform' => $this->get('session')->get('platform'),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            // $em->persist($address);
            // $em->flush();
            //
            // return $this->redirectToRoute('app_contact', ['contact' => $person->getID()]);
        }

        return $this->render('WebBundle:Platform:adduser.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
