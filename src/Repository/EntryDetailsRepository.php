<?php

namespace App\Repository;

use App\Entity\EntryDetails;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method EntryDetails|null find($id, $lockMode = null, $lockVersion = null)
 * @method EntryDetails|null findOneBy(array $criteria, array $orderBy = null)
 * @method EntryDetails[]    findAll()
 * @method EntryDetails[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EntryDetailsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EntryDetails::class);
    }

    public function getSpearsPartsListByCondition($fromDate, $toDate, $spareParts, $status = 1, $car){
        $fromDate = ($fromDate!="") ? date("Y-m-d", strtotime($fromDate)) : date('Y-m-d');
        $toDate   = ($toDate!="") ? date("Y-m-d", strtotime($toDate)) : $fromDate;

        $sql ='SELECT ed.*,sp.name as spears_name,c.name,c.chessis
FROM `entry_details` ed
INNER JOIN `spare_parts` sp ON ed.`spare_parts_id` = sp.`id`
INNER JOIN `car` c ON ed.`car_id` = c.`id`
WHERE ed.`status` = "'.$status.'"  AND DATE(ed.`create_at`)  BETWEEN  DATE("'.$fromDate.'") AND DATE("'.$toDate.'")
';
        $condition = '';
        if($spareParts!=""){
            $condition.=' AND ed.`spare_parts_id` = "'.$spareParts.'"';
        }
        if($car!=""){
            $condition.=' AND ed.`car_id` = "'.$car.'"';
        }
        $sql =$sql.$condition.'  ORDER BY ed.`create_at` DESC' ;
        //echo $sql;die();

        return $this->getEntityManager()->getConnection()->executeQuery($sql)->fetchAll();
    }

    // /**
    //  * @return EntryDetials[] Returns an array of EntryDetials objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EntryDetials
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
