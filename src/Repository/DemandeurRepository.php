<?php

namespace App\Repository;

use App\Entity\Demandeur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Demandeur>
 */
class DemandeurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Demandeur::class);
    }

    public function findByPartialNomOld(string $demandeur)
    {
        return $this->createQueryBuilder('a')
            ->where('a.nom LIKE :demandeur')
            ->setParameter('demandeur', '%' . $demandeur . '%')
            ->setMaxResults(1)  // Limiter à un seul résultat
            ->getQuery()
            ->getOneOrNullResult(); // Retourne un seul objet ou null si aucun résultat
    }

    public function findByPartialNom(string $demandeur)
    {
        return $this->createQueryBuilder('a')
            ->where(':demandeur LIKE CONCAT(\'%\', a.nom, \'%\')')
            ->setParameter('demandeur', $demandeur)
            ->setMaxResults(1)  // Limiter à un seul résultat
            ->getQuery()
            ->getOneOrNullResult(); // Retourne un seul objet ou null si aucun résultat
    }

    //    /**
    //     * @return Demandeur[] Returns an array of Demandeur objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('d.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Demandeur
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
