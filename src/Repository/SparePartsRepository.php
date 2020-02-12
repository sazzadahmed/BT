<?php

namespace App\Repository;

use App\Entity\SpareParts;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method SpareParts|null find($id, $lockMode = null, $lockVersion = null)
 * @method SpareParts|null findOneBy(array $criteria, array $orderBy = null)
 * @method SpareParts[]    findAll()
 * @method SpareParts[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SparePartsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SpareParts::class);
    }

    // /**
    //  * @return SpareParts[] Returns an array of SpareParts objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SpareParts
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
