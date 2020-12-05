<?php

namespace App\Repository;

use App\Entity\TicketLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TicketLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method TicketLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method TicketLog[]    findAll()
 * @method TicketLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TicketLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TicketLog::class);
    }

    // /**
    //  * @return TicketLog[] Returns an array of TicketLog objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TicketLog
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
