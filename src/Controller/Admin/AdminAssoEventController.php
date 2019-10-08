<?php

namespace App\Controller\Admin;

use App\Entity\AssoEvent;
use App\Entity\Organization;
use App\Form\AssoEventType;
use App\Repository\AssoEventRepository;
use App\Repository\BelongToRepository;
use App\Repository\OrganizationRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/event", name="admin_event_")
 */
class AdminAssoEventController extends AbstractController
{
    private $organizationRepository;
    private $repository;
    private $em;

    /**
     * AdminAssoEventController constructor.
     * @param AssoEventRepository $repository
     * @param OrganizationRepository $organizationRepository
     * @param ObjectManager $em
     */
    public function __construct(AssoEventRepository $repository, OrganizationRepository $organizationRepository, ObjectManager $em)
    {
        $this->organizationRepository = $organizationRepository;
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @Route("/", name="home", methods={"GET"})
     * @return Response
     */
    public function calendar(): Response
    {
        return $this->render('calendar/calendar.html.twig');
    }

    /**
     * @Route("/choice_organization", name="choice_organization")
     * @param BelongToRepository $belongToRepository
     * @return Response
     */
    public function choiceOrganization(BelongToRepository $belongToRepository): Response
    {
        $a_organizations = $this->getUser()->getOrganizations();
        $user = $this->getUser()->getId();

        $b_organizations = $belongToRepository->findBelongToIdWhithUser($user);

        if (!empty($a_organizations) || !empty($b_organizations)) {

            return $this->render('admin/organization/selectOrganization.html.twig', [
                'a_organizations' => $a_organizations,
                'b_organizations' => $b_organizations
            ]);
        }
        return $this->redirectToRoute();
    }

    /**
     * @Route("/new/{id}", name="new", methods={"GET","POST"})
     * @Entity("organization", expr="repository.find(id)")
     * @param OrganizationRepository $organizationRepository
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function new(OrganizationRepository $organizationRepository, Request $request, $id): Response
    {
        $organization = $organizationRepository->find($id);
        $user = $this->getUser();
        $assoEvent = new AssoEvent();

        $form = $this->createForm(AssoEventType::class, $assoEvent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $organizationsChoices = $form->get('organizationsPartner')->getData();
            dump($organizationsChoices);die;
            foreach ($organizationsChoices as $choice) {
                $assoEvent->addOrganizationPartner($choice);
            }
            $assoEvent->setOrganization($organization);
            $assoEvent->setUser($user);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($assoEvent);

            $entityManager->flush();
            $this->addFlash('success', 'Votre événement a été créé avec succès');
            return $this->redirectToRoute('admin_event_home');
        }

        return $this->render('admin/asso_event/new.html.twig', [
            'assoEvent' => $assoEvent,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/index", name="index", methods={"GET"})
     * @param AssoEventRepository $assoEventRepository
     * @param OrganizationRepository $organizationRepository
     * @return Response
     */
    public function index(AssoEventRepository $assoEventRepository, OrganizationRepository $organizationRepository): Response
    {
        $admin = $this->getUser()->getId();

        $assoEvents = $assoEventRepository->findBy(array('user' => $admin));

        foreach ($assoEvents as $assoEvent) {
                $organization = $organizationRepository->find($assoEvent->getId());

                return $this->render('admin/asso_event/index.html.twig', [
                    'assoEvents' => $assoEvents,
                    'organization' => $organization
                ]);
            }
        return  $this->redirectToRoute('admin_event_home');
    }


    /**
     * @Route("/evenements/super_admin", name="super_admin", methods={"GET"})
     * @param AssoEventRepository $assoEventRepository
     * @param OrganizationRepository $organizationRepository
     * @return Response
     */
    public function indexForSuperAdmin(AssoEventRepository $assoEventRepository, OrganizationRepository $organizationRepository): Response
    {
        $admin = $this->getUser()->getId();

        $organizations = $organizationRepository->findBy(array('user' => $admin));

        $assoEvents = $assoEventRepository->findBy(array('organization' => $organizations));

        if ($assoEvents != null) {
            foreach ($assoEvents as $assoEvent) {
                $assoEvent->getOrganization();
            }
            return $this->render('admin/asso_event/indexSuperAdmin.html.twig', [
                'assoEvents' => $assoEvents,
            ]);
        } else {
            return $this->redirectToRoute('admin_event_home');
        }
    }

    /**
     * @Route("/show/{id}", name="show_is_admin", methods={"GET"})
     * @param AssoEvent $assoEvent
     * @return Response
     */
    public function show(AssoEvent $assoEvent): Response
    {
        $getpartners = $assoEvent->getOrganizationsPartner();
        $partners = $getpartners->getValues();
        return $this->render('admin/asso_event/show.html.twig', [
            'assoEvent' => $assoEvent,
            'partners'=> $partners
        ]);
    }

    /**
     * @Route("/is_not_admin/show/{id}", name="show", methods={"GET"})
     * @param AssoEvent $assoEvent
     * @param OrganizationRepository $organizationRepository
     * @return Response
     */
    public function showIsNotAdmin(AssoEvent $assoEvent, OrganizationRepository $organizationRepository): Response
    {
        $org = $assoEvent->getOrganization();
        $organization = $organizationRepository->find($org);
        $getpartners = $assoEvent->getOrganizationsPartner();
        $partners = $getpartners->getValues();

        return $this->render('admin/asso_event/show_without_admin.html.twig', [
            'assoEvent' => $assoEvent,
            'organization' => $organization,
            'partners' => $partners
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param AssoEvent $assoEvent
     * @return Response
     */
    public function edit(Request $request, AssoEvent $assoEvent): Response
    {
        $organization = $assoEvent->getOrganization()->getId();
       // dump($organization);die;
        $partners = $assoEvent->getOrganizationsPartner()->toArray();
       //$array = array_diff($partners, $organization);
       // $partners = $getpartners->getValues();
       //dump($array);die;
        $form = $this->createForm(AssoEventType::class, $assoEvent);
        $form->add('organizationsPartner', EntityType::class, [
            'label' => false,
            'class' => Organization::class,
            'query_builder' =>
                function (OrganizationRepository $er) use ($organization) {
                    return $er->createQueryBuilder('org')
                        ->where('org.id != :id')
                        ->setParameter('id', $organization)
                        ;
                },
            'required' => false,
            'choice_label' => 'name',
            'expanded' => true,
            'multiple' => true,
            'mapped' => false
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $organizationsChoices = $form->get('organizationsPartner')->getData();
            foreach ($organizationsChoices as $choice) {
                $assoEvent->addOrganizationPartner($choice);
            }
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Votre événement a été modifié avec succès');
            return $this->redirectToRoute('admin_event_index', [
                'id' => $assoEvent->getId(),
            ]);
        }
        return $this->render('admin/asso_event/edit.html.twig', [
            'assoEvent' => $assoEvent,
            'partners'=>$partners,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param AssoEvent $assoEvent
     * @return Response
     */
    public function delete(Request $request, AssoEvent $assoEvent): Response
    {
        if ($this->isCsrfTokenValid('delete' . $assoEvent->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($assoEvent);
            $entityManager->flush();
        }
        $this->addFlash('warning', 'Votre événement a été supprimé avec succès');
        return $this->redirectToRoute('admin_event_index');
    }

    /**
     * @Route("/rgpd", name="rgpd", methods={"GET"})
     */
    public function rgpd(): Response
    {
        return $this->render('admin/rgpd.html.twig');
    }
}
