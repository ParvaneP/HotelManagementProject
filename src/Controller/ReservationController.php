<?php 

namespace App\Controller;

use App\Entity\User;
use App\Entity\Reservation;
use App\Form\ReservationDurationFormType;
use App\Form\ReservationFormType;
use App\Service\ReservationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/reservations')]
class ReservationController extends AbstractController
{
    /* 
     * @var  ReservationService
     */
    private $reservationService;

    public function __construct(ReservationService $reservationService)
    {
        $this->reservationService = $reservationService;
    }

    #[Route('/', name: 'app_reservation_index')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $reservations = $entityManager->getRepository(Reservation::class)->findAll();

        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservations,
        ]);
    }

    #[Route('/new', name: 'app_reservation_new')]
    public function new(Request $request, EntityManagerInterface $entityManager, ReservationService $reservationService): Response
    {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationFormType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($reservationService->isRoomAvailable($reservation->getRoom(), $reservation->getStartDate(), $reservation->getEndDate())) {
                $entityManager->persist($reservation);
                $entityManager->flush();

                // Send confirmation email (use EmailService)
                // $emailService->sendConfirmation($reservation);

                return $this->redirectToRoute('app_reservation_index');
            } else {
                $this->addFlash('error', 'The room is not available for the selected dates.');
            }
        }

        return $this->render('reservation/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/rooms', name: 'reservation_room', methods: ['GET', 'POST'])]
    public function reservationRoom(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReservationDurationFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $availableRooms = $this->reservationService->findAvailableRooms($form->get('startDate')->getData(), $form->get('endDate')->getData());
            return $this->render('reservation/guest_reservation.html.twig', [
                'rooms' => $availableRooms,
                'form' => $form,
            ]);
        }

        return $this->render('reservation/guest_reservation.html.twig', [
            'rooms' => [],
            'form' => $form,
        ]);
    }

    #[Route('/my_reservation', name: 'app_guest_reservation_index')]
    public function guestReservationIndex(EntityManagerInterface $entityManager): Response
    {
        $reservations = [];
        $user = $this->getUser();
        if ($user instanceof User) {
            $userId = $user->getId();
            $reservations = $entityManager->getRepository(Reservation::class)->findReservationsUserId($userId);
        }
        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservations,
        ]);
    }
    
    #[Route('/{id}', name: 'app_reservation_show', methods: ['GET'])]
    public function show(Reservation $reservation): Response
    {
        return $this->render('reservation/show.html.twig', [
            'reservation' => $reservation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reservation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReservationFormType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reservation/edit.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'reservation_delete', methods: ['POST'])]
    public function delete(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reservation->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($reservation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
    }
}