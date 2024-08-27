<?php

namespace App\Repository;

use App\Entity\Room;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Room>
 */
class RoomRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Room::class);
    }

    
    /**
     * @param \DateTimeInterface $checkIn
     * @param \DateTimeInterface $checkOut
     * @return Room[]
     */
    public function findAvailableRooms(\DateTimeInterface $checkIn, \DateTimeInterface $checkOut): array
    {
        return $this->createQueryBuilder('r')
            ->leftJoin('App\Entity\Reservation', 'res', 'WITH', 'res.room = r.id')
            ->where('res.id IS NULL OR (res.endDate <= :checkIn OR res.startDate >= :checkOut)')
            ->setParameter('checkIn', $checkIn)
            ->setParameter('checkOut', $checkOut)
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @param \DateTimeInterface $checkIn
    //  * @param \DateTimeInterface $checkOut
    //  * @return Room[]
    //  */
    // public function findAvailableRooms(\DateTimeInterface $checkIn, \DateTimeInterface $checkOut): array
    // {
    //     $qb = $this->createQueryBuilder('r');

    //     $qb->leftJoin('r.rescervations', 'res')
    //        ->andWhere('(res.checkOut < :checkOut AND res.checkOut > :checkIn) OR (res.checkIn < :checkOut AND res.checkIn > :checkIn)')
    //        ->andWhere('res.status IN (:statuses)')
    //        ->setParameter('checkIn', $checkIn)
    //        ->setParameter('checkOut', $checkOut)
    //        ->setParameter('statuses', ['pending', 'confirmed']);

    //     return $qb->getQuery()->getResult();
    // }

    //    /**
    //     * @return Room[] Returns an array of Room objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Room
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
