<?php

namespace App\Repository;

use App\Entity\Comments;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Comments|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comments|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comments[]    findAll()
 * @method Comments[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comments::class);
    }

    public function commentsOnePost($post)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.idPost = :val')
            ->setParameter('val', $post)
            ->orderBy('c.dateOfComment', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }


    public function numberCommentsOnePost($post)
    {
        return $this->createQueryBuilder('c')
            ->select('count(c.id)')
            ->andWhere('c.idPost = :val')
            ->setParameter('val', $post)
            ->getQuery()
            ->getSingleScalarResult();
        ;
    }


    public function getIdCommentsOnePost($post)
    {
        return $this->createQueryBuilder('c')
            ->select('c.id')
            ->andWhere('c.idPost = :val')
            ->setParameter('val', $post)
            ->getQuery()
            ->getResult();
        ;
    }


    public function getIdCommentsOneUser($user)
    {
        return $this->createQueryBuilder('c')
            ->select('c.id')
            ->andWhere('c.idUser = :val')
            ->setParameter('val', $user)
            ->getQuery()
            ->getResult();
        ;
    }


    // /**
    //  * @return Comments[] Returns an array of Comments objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Comments
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
