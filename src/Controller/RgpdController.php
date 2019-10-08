<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/", name="mentions_")
 */
class RgpdController extends AbstractController
{
    /**
     * @Route("/rgpd", name="rgpd")
     */
    public function rgpd():Response
    {
        return $this->render('rgpd/rgpd.html.twig');
    }
}