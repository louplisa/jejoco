<?php


namespace App\Controller;


use App\Entity\AssoEvent;
use App\Repository\OrganizationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/", name="jejoco_")
 */
class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home", methods={"GET"})
     * @return Response
     */
    public function calendar(): Response
    {
        return $this->render('calendar/calendar.html.twig');
    }
}