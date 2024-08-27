<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LandingController extends AbstractController
{
    #[Route('/', name: 'app_landing_page')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        return $this->render('landing/index.html.twig');
    }
}
