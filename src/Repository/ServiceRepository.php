<?php

namespace App\Repository;

use App\Entity\Service;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Service>
 */
class ServiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Service::class);
    }

    public function findByCriteria($adresse = null, $chauffeur = null, $demandeur = null, $startDate = null, $endDate = null)
    {
        $qb = $this->createQueryBuilder('s');

        if ($adresse) {
            // Filtrer par pickUpFrom ou pickUpTo
            $qb->andWhere('s.pickUpFrom = :adresse OR s.pickUpTo = :adresse')
                ->setParameter('adresse', $adresse);
        }

        if ($chauffeur) {
            $qb->andWhere('s.chauffeur = :chauffeur')
                ->setParameter('chauffeur', $chauffeur);
        }

        if ($demandeur) {
            $qb->andWhere('s.demandeur = :demandeur')
                ->setParameter('demandeur', $demandeur);
        }

        if ($startDate && $endDate) {
            $qb->andWhere('s.serviceAt BETWEEN :startDate AND :endDate')
                ->setParameter('startDate', $startDate)
                ->setParameter('endDate', $endDate);
        }

        return $qb->getQuery()->getResult();
    }

    //    /**
    //     * @return Service[] Returns an array of Service objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Service
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
