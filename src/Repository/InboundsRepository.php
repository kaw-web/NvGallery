<?php

namespace App\Repository;

use App\Entity\Inbounds;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Inbounds>
 *
 * @method Inbounds|null find($id, $lockMode = null, $lockVersion = null)
 * @method Inbounds|null findOneBy(array $criteria, array $orderBy = null)
 * @method Inbounds[]    findAll()
 * @method Inbounds[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InboundsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Inbounds::class);
    }

    //    /**
    //     * @return Inbounds[] Returns an array of Inbounds objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('i.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Inbounds
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
