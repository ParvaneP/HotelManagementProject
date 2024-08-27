<?php 

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Repository\RoomRepository;

class ReservationService
{
    private $entityManager;
    private $roomRepository;

    public function __construct(EntityManagerInterface $entityManager, RoomRepository $roomRepository)
    {
        $this->entityManager = $entityManager;
        $this->roomRepository = $roomRepository;
    }

    public function findAvailableRooms($checkInDate, $checkOutDate)
    {
        return $this->roomRepository->findAvailableRooms($checkInDate, $checkOutDate);
    }

    public function isRoomAvailable($room, $checkInDate, $checkOutDate)
    {
        return $this->roomRepository->findAvailableRooms($checkInDate, $checkOutDate);
    }
}