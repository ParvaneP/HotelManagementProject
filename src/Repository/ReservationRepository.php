<?php

namespace App\Repository;

use App\Entity\Reservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reservation>
 */
class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    /**
    * @param string $value
    * @return Reservation[] Returns an array of Reservation objects
    */
   public function findReservationsUserId($value): array
   {
        return $this->createQueryBuilder('r')
        ->andWhere('r.user = :userId')
        ->setParameter('userId', $value)
        ->getQuery()
        ->getResult();
   }

   /**
    * @param string $value
    * @return Reservation[] Returns an array of Reservation objects
    */
   public function findReservationsByStatus($value): array
   {
       return $this->createQueryBuilder('r')
           ->andWhere('r.status = :val')
           ->setParameter('val', $value)
           ->orderBy('r.id', 'ASC')
           ->getQuery()
           ->getResult();
   }
}
