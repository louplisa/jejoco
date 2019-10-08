<?php

namespace App\Controller\Admin;

use App\Entity\BelongTo;
use App\Entity\Organization;
use App\Entity\User;
use App\Form\EditUserProfile;
use App\Repository\BelongToRepository;
use App\Repository\OrganizationRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_USER")
 * @Route("/admin/user", name="admin_user_")
 */
class AdminUserController extends AbstractController
{
    /**
     * @Route("/choice_organization", name="choice_organization")
     */
    public function choiceOrganizationForUserAdmin(): Response
    {
        $organizations = $this->getUser()->getOrganizations();

        return $this->render('admin/organization/selectOrganizationForUserAdmin.html.twig', [
            'organizations' => $organizations
        ]);
    }

    /**
     * @Route("/user_choice/{id}", name="choice")
     * @param Request $request
     * @param BelongToRepository $belongToRepository
     * @return Response
     */
    public function choiceUser(Request $request, BelongToRepository $belongToRepository): Response
    {
        $ids = $request->get('idOrg');
        $users = $belongToRepository->findByUserIsNotAdmin($ids);

        return $this->render('admin/user/selectUser.html.twig', [
            'users' => $users
        ]);
    }

    /**
     * @Route("/setadmin/{id}", name="status_change", methods={"GET", "POST"} )
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function userIsAdmin(Request $request, BelongToRepository $belongToRepository, EntityManagerInterface $entityManager): Response
    {
        /*
         * get('myIds')=> On le récupère dans la vue <input type="checkbox" name="myIds[]" value="{{ user.id }}">
         */
        $ids = $request->get('myIds');

        foreach ($ids as $idUser) {

            $user = $belongToRepository->find($idUser);

            $user->setIsAdmin(true);

            $entityManager->persist($user);
            $entityManager->flush();
        }
        return $this->redirectToRoute('admin_event_home');
    }

    /**
     * @Route("/profile/{id}", name="show", methods={"GET"})
     * @param User $user
     * @return Response
     */
    public function show(User $user): Response
    {
        return $this->render('admin/user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/admin/account", name="account")
     * @param LoggerInterface $logger
     * @return RedirectResponse
     */
    public function index(LoggerInterface $logger)
    {
        $logger->debug('Checking account page for ' . $this->getUser()->getEmail());

        return $this->redirectToRoute('admin_event_home');
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET", "POST"})
     * @param Request $request
     * @param User $user
     * @return Response
     */
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(EditUserProfile::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Votre profil a été modifié avec succès');
            return $this->redirectToRoute('admin_event_home');
        }

        return $this->render('admin/user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/organizations", name="organizations")
     * @param Request $request
     * @return Organization|Response
     */
    public function choicesOrganization(Request $request, BelongToRepository $belongToRepository, OrganizationRepository $organizationRepository)
    {
        $user = $this->getUser()->getId();

        $organizations = $organizationRepository->findOrganizationWithoutUser($user);
        $organizations_member = $belongToRepository->findBelongToIdWhithUserNotAdmin($user);
        $belongTo = $belongToRepository->findBelongToIdWhithUser($user);

        $array_fusion = array_merge($organizations, $organizations_member, $belongTo);
        $array = array_column($array_fusion, 'id');

        $count = array_count_values($array);

        $results = array_filter($array, function (int $value) use ($count) {
            return 1 === $count[$value];
        });

       $orgs = $organizationRepository->findBy(array('id'=>$results));

        return $this->render('admin/belong_to/new.html.twig', [
            'orgs' => $orgs,
        ]);
    }


    /**
     * @Route("/choiceUsers/{id}", name="choice_org", methods={"GET", "POST"})
     * @param Request $request
     * @param UserRepository $userRepository
     * @param EntityManagerInterface $entityManager
     * @return RedirectResponse
     */
    public
    function choiceUsers(Request $request, OrganizationRepository $organizationRepository, EntityManagerInterface $entityManager)
    {
        $ids = $request->get('myIdsOrg');
       // $test =$organizationRepository->find($ids);
     // dump($test);die;

        foreach ($ids as $idOrg) {
           $idOrg= $organizationRepository->find($idOrg);
            $belong_to = new BelongTo();
            $belong_to->setIsAdmin(false);
            $belong_to->setOrganizations($idOrg);
            $belong_to->setUser($this->getUser());
            $entityManager->persist($belong_to);
            $entityManager->flush();
        }
        return $this->redirectToRoute('admin_event_home');
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param User $user
     * @return Response
     */
    public
    function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }
        $this->addFlash('warning', 'Votre profil a été supprimé avec succès');
        return $this->redirectToRoute('logout');
    }
}
