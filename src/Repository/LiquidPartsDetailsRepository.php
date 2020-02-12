<?php

namespace App\Repository;

use App\Entity\LiquidPartsDetails;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method LiquidPartsDetails|null find($id, $lockMode = null, $lockVersion = null)
 * @method LiquidPartsDetails|null findOneBy(array $criteria, array $orderBy = null)
 * @method LiquidPartsDetails[]    findAll()
 * @method LiquidPartsDetails[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LiquidPartsDetailsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LiquidPartsDetails::class);
    }

    public function getAvailAbleWastage( $spareParts){

        $sql =' SELECT 
 (x.total_quantity - x.total_sold) AS available_quantity
  FROM ( SELECT lp.`spears_parts_id`,( SELECT SUM( lp.`wastage_quantity` )  
                    FROM `liquid_parts_details` lp
                   WHERE lp.`status` = 1 AND lp.`spears_parts_id` = "'.$spareParts.'" ) AS total_quantity,
                   (SELECT SUM( lp.`sold_quantity` )  
                    FROM `liquid_parts_details` lp
                   WHERE lp.`status` = 1 AND lp.`spears_parts_id` = "'.$spareParts.'"  ) AS total_sold
       FROM `liquid_parts_details` lp WHERE lp.`status` = 1 AND lp.`spears_parts_id` = "'.$spareParts.'") x
 GROUP BY x.`spears_parts_id`';


        //echo $sql;die();

        return $this->getEntityManager()->getConnection()->executeQuery($sql)->fetch();
    }

    public function getLiquidSpearsPartsListByCondition($fromDate, $toDate, $spareParts, $status = 2){
       // echo $status;
        $fromDate = ($fromDate!="") ? date("Y-m-d", strtotime($fromDate)) : date('Y-m-d');
        $toDate   = ($toDate!="") ? date("Y-m-d", strtotime($toDate)) : $fromDate;

        $sql ='SELECT lpd.*,sp.name AS spears_name
FROM `liquid_parts_details` lpd 
INNER JOIN `spare_parts` sp ON lpd.`spears_parts_id` = sp.`id` 
WHERE lpd.`status` = 1 AND DATE(lpd.`create_at`) BETWEEN  DATE("'.$fromDate.'") AND DATE("'.$toDate.'") 
';
        $condition = '';
        if($spareParts!=""){
            $condition.=' AND lpd.`spears_parts_id` = "'.$spareParts.'"';
        }
        if($status=="2"){
            $condition.=' AND lpd.`wastage_quantity`>0';
        }
        if($status=="3"){
            $condition.=' AND lpd.`sold_quantity`>0';
        }


        $sql =$sql.$condition.'  ORDER BY lpd.`create_at` ASC' ;
        //echo $sql;die();

        return $this->getEntityManager()->getConnection()->executeQuery($sql)->fetchAll();
    }



    // /**
    //  * @return LiquidPartsDetails[] Returns an array of LiquidPartsDetails objects
    //  */
    /*
     *
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LiquidPartsDetails
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
