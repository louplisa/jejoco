<?php

namespace App\Controller;

use App\Entity\AssoEvent;
use App\Repository\OrganizationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/event", name="event_")
 */
class AssoEventController extends AbstractController
{

    /**
     * @Route("/show/{id}", name="show", methods={"GET"})
     * @param AssoEvent $assoEvent
     * @return Response
     */
    public function show(AssoEvent $assoEvent, OrganizationRepository $organizationRepository): Response
    {
        /*
         * If user has 'ROLE_USER', he's redirect to admin space
         * to controller 'AdminAssoEventController', and he can edit and delete events
         */
        if ($this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('admin_event_show', [
                'id' => $assoEvent->getId(),
                'assoEvent' => $assoEvent
            ]);
        }else{
            $org = $assoEvent->getOrganization();
            $organization = $organizationRepository->find($org);
            $getpartners = $assoEvent->getOrganizationsPartner();
            $partners = $getpartners->getValues();

            return $this->render('asso_event/show.html.twig', [
                'assoEvent' => $assoEvent,
                'organization' => $organization,
                'partners' => $partners
            ]);
        }

        }

}
