<?php

namespace App\Repository;

use App\Entity\CashRegisterJournal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CashRegisterJournal>
 *
 * @method CashRegisterJournal|null find($id, $lockMode = null, $lockVersion = null)
 * @method CashRegisterJournal|null findOneBy(array $criteria, array $orderBy = null)
 * @method CashRegisterJournal[]    findAll()
 * @method CashRegisterJournal[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CashRegisterJournalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CashRegisterJournal::class);
    }

    //    /**
    //     * @return CashRegisterJournal[] Returns an array of CashRegisterJournal objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?CashRegisterJournal
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
