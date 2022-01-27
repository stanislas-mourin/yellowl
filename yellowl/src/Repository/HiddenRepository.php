<?php

namespace App\Repository;

use App\Entity\Hidden;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Hidden|null find($id, $lockMode = null, $lockVersion = null)
 * @method Hidden|null findOneBy(array $criteria, array $orderBy = null)
 * @method Hidden[]    findAll()
 * @method Hidden[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HiddenRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Hidden::class);
    }

    // /**
    //  * @return Hidden[] Returns an array of Hidden objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('h.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Hidden
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
