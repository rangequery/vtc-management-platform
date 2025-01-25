<?php

namespace App\Repository;

use App\Entity\Adresse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Adresse>
 */
class AdresseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Adresse::class);
    }

    public function findByPartialNomOld(string $destination)
    {
        return $this->createQueryBuilder('a')
            ->where('a.nom LIKE :destination')
            ->setParameter('destination', '%' . $destination . '%')
            ->setMaxResults(1)  // Limiter à un seul résultat
            ->getQuery()
            ->getOneOrNullResult(); // Retourne un seul objet ou null si aucun résultat
    }

    public function findByPartialNom(string $adresse)
    {
        return $this->createQueryBuilder('a')
            ->where(':adresse LIKE CONCAT(\'%\', a.nom, \'%\')')
            ->setParameter('adresse', $adresse)
            ->setMaxResults(1)  // Limiter à un seul résultat
            ->getQuery()
            ->getOneOrNullResult(); // Retourne un seul objet ou null si aucun résultat
    }

    //    /**
    //     * @return Adresse[] Returns an array of Adresse objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Adresse
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
