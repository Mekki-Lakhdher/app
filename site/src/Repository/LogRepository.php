<?php

namespace App\Repository;

use App\Entity\Log;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Log|null find($id, $lockMode = null, $lockVersion = null)
 * @method Log|null findOneBy(array $criteria, array $orderBy = null)
 * @method Log[]    findAll()
 * @method Log[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Log::class);
    }

    // /**
    //  * @return Log[] Returns an array of Log objects
    //  */
    /*
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
    public function findOneBySomeField($value): ?Log
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function countLogEntries($service_names,$start_date,$end_date,$status_code)
    {
        $conn = $this->getEntityManager()->getConnection();
        // prepare query
        $service_names_condition=self::prepareServiceNamesCondition($service_names);
        $start_date_condition=self::prepareStartDateCondition($start_date);
        $end_date_condition=self::prepareEndDateCondition($end_date);
        $status_code_condition=self::prepareStatusCodeCondition($status_code);     
        $sql = "
            SELECT COUNT(*) AS `counter` FROM `dev`.`log`
            WHERE 1 
            AND ($service_names_condition)
            AND $start_date_condition
            AND $end_date_condition
            AND $status_code_condition
        ";
        $stmt = $conn->prepare($sql);
        return $stmt->execute()->fetch();
    }

    public function prepareServiceNamesCondition($service_names)
    {
        if ($service_names===null || !isset($service_names[0])) {
            $service_names_condition="1";
        }
        else
        {
            $service_names_condition="";
            foreach ($service_names as $service_name) {
                $service_names_condition.="OR `service` LIKE '%$service_name%' ";
            }
        }
        return ltrim($service_names_condition,"OR ");
    }

    public function prepareStartDateCondition($start_date)
    {
        if ($start_date===null) {
            $start_date_condition="1";
        }
        else
        {
            $start_date_condition="`date_time` >= '$start_date'";
        }
        return $start_date_condition;
    }

    public function prepareEndDateCondition($end_date)
    {
        if ($end_date===null) {
            $end_date_condition="1";
        }
        else
        {
            $end_date_condition="`date_time` <= '$end_date'";
        }
        return $end_date_condition;
    }

    public function prepareStatusCodeCondition($status_code)
    {
        if ($status_code===null) {
            $status_code_condition="1";
        }
        else
        {
            $status_code_condition="`status_code`=$status_code";
        }
        return $status_code_condition;
    }

}
