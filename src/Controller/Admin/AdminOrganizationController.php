<?php

namespace App\Controller\Admin;

use App\Entity\Organization;
use App\Form\OrganizationType;
use App\Repository\OrganizationRepository;
use DateTime;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/organization", name="admin_organization_")
 */
class AdminOrganizationController extends AbstractController
{
    /**
     * @Route("/index", name="index", methods={"GET"})
     * @param OrganizationRepository $organizationRepository
     * @return Response
     */
    public function index(OrganizationRepository $organizationRepository): Response
    {
        $admin = $this->getUser()->getId();

        $organizations = $organizationRepository->findAllInformationsOrganizationWithUser($admin);

        return $this->render('admin/organization/index.html.twig', [
            'organizations' => $organizations
        ]);
    }

    /**
     * @Route("/new", name="new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function new(Request $request): Response
    {
        /*
         * get current user for foreign key into Organization entity
         */
        $user = $this->getUser();

        $organization = new Organization();

        $form = $this->createForm(OrganizationType::class, $organization);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /*
             * current user is automatically define to 'isAdmin',
             *  because he is the creator of organization
             */

            /*
             * Put on the entity the datetime register
             */
            $organization->setRegisteredAt(new DateTime());

            /*
             * Put on the entity the foreign key id matching with the primary key
             * to entity User
             */
            $organization->setUser($user);

            $entityManager = $this->getDoctrine()->getManager();
            /*
             * Prepare the insert data to db, with in parameter the data of entity
             */
            $entityManager->persist($organization);
            /*
             *Insert data to db
             */
            $entityManager->flush();
            /*
             * After submission the user is redirect to this route specified
             */
            $this->addFlash('success', 'Votre association a été créée avec succès');
            return $this->redirectToRoute('admin_event_home');
        }
        /*
         * The route for to access at form for create organization
         * The parameters 'organization' and 'form' enable to create variables
         * to the view file twig
         */
        return $this->render('admin/organization/new.html.twig', [
            'organization' => $organization,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/show/{id}", name="show", methods={"GET"})
     * @param Organization $organization
     * @return Response
     */
    public function show(Organization $organization): Response
    {
       // $organizations = $organizationRepository->findAll();

        return $this->render('admin/organization/show.html.twig', [
            'organization' => $organization,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param Organization $organization
     * @return Response
     */
    public function edit(Request $request, Organization $organization): Response
    {
        $form = $this->createForm(OrganizationType::class, $organization);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Votre association a été modifié avec succès');
            return $this->redirectToRoute('admin_organization_index', [
                'id' => $organization->getId(),
            ]);
        }
        return $this->render('admin/organization/edit.html.twig', [
            'organization' => $organization,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param Organization $organization
     * @return Response
     */
    public function delete(Request $request, Organization $organization): Response
    {
        if ($this->isCsrfTokenValid('delete' . $organization->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($organization);
            $entityManager->flush();
        }
        $this->addFlash('warning', 'Votre association a été supprimée avec succès');
        return $this->redirectToRoute('admin_organization_index');
    }
}
