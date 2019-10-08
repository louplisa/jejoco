<?php

namespace App\Controller;

use App\Entity\BelongTo;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\OrganizationRepository;
use DateTime;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/inscription")
 */
class UserInscriptionController extends AbstractController
{
    /**
     * @Route("/", name="inscription", methods={"GET","POST"})
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param OrganizationRepository $organizationRepository
     * @return Response
     * @throws Exception
     */
    public function new(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setRegisteredAt(new DateTime('now'));

            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);

            $entityManager->flush();

            $organizationsChoices = $form->get('belongTos')->getData();

            foreach ($organizationsChoices as $choice) {
                $belongTos = new BelongTo();

                $user->addBelongTo($belongTos);
                $belongTos->setIsAdmin(false);

                $belongTos->setOrganizations($choice);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($belongTos);

                $entityManager->flush();
            }
            $this->addFlash('success', 'Votre inscription a bien été enregistrée');
            return $this->redirectToRoute('login', [
                'id' => $user->getId()
            ]);
        }
        return $this->render('user/inscription.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }
}