<?php

namespace App\Controller;

use App\Entity\Room;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LandingController extends AbstractController
{
    #[Route('/', name: 'landing_page')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Fetch all rooms from the database
        $rooms = $entityManager->getRepository(Room::class)->findAll();

        return $this->render('landing/index.html.twig', [ 
            'rooms' => $rooms,
        ]);
    }
}
