<?php

namespace App\Repository;

use App\Entity\Posts;
use App\Entity\Users;
use App\Entity\Categories;
use App\Repository\Join;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Posts|null find($id, $lockMode = null, $lockVersion = null)
 * @method Posts|null findOneBy(array $criteria, array $orderBy = null)
 * @method Posts[]    findAll()
 * @method Posts[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Posts::class);
    }


    public function searchTitle($searchText)
    {
        return $this->createQueryBuilder('p')
            ->select('p')
            ->andWhere('p.title LIKE :val')
            ->setParameter('val',  '%'.$searchText.'%')
            ->orderBy('p.dateOfPost', 'DESC')
            ->getQuery()
            ->getResult();
        ;
    }


    public function numberLikesPerPost()
    {
        return $this->createQueryBuilder('p')
            ->select('p.id, p.title, p.media, p.likes')
            ->orderBy('p.likes', 'DESC')
            ->getQuery()
            ->getResult();
        ;
    }


    public function numberDislikesPerPost()
    {
        return $this->createQueryBuilder('p')
            ->select('p.id, p.title, p.media, p.dislikes')
            ->orderBy('p.dislikes', 'DESC')
            ->getQuery()
            ->getResult();
        ;
    }


    public function numberPostPerDay()
    {
        return $this->createQueryBuilder('p')
            ->select('p.dateOfPost, count(p.id)')
            ->groupBy('p.dateOfPost')
            ->getQuery()
            ->getResult();
        ;
    }


    public function nicknameUserOfPost($id)
    {
        return $this->createQueryBuilder('p')
            ->select('u.nickName')
            ->Join(Users::class, 'u', 'WITH', 'u.id = p.idUser')
            ->andWhere('p.id = :val')
            ->setParameter('val',  $id)
            ->getQuery()
            ->getSingleScalarResult();
        ;
    }


    public function categoryNameOfPost($id)
    {
        return $this->createQueryBuilder('p')
            ->select('c.namecategory')
            ->Join(Categories::class, 'c', 'WITH', 'c.id = p.idCategory')
            ->andWhere('p.id = :val')
            ->setParameter('val',  $id)
            ->getQuery()
            ->getSingleScalarResult();
        ;
    }


    public function numberPostByCategory()
    {
        return $this->createQueryBuilder('p')
            ->select('c.namecategory', 'count(p.id)')
            ->Join(Categories::class, 'c', 'WITH', 'c.id = p.idCategory')
            ->groupBy('c.namecategory')
            ->orderBy('count(p.id)', 'DESC')
            ->getQuery()
            ->getResult();
        ;
    }


    public function numberLikesByCategory()
    {
        return $this->createQueryBuilder('p')
            ->select('c.namecategory', 'sum(p.likes)')
            ->Join(Categories::class, 'c', 'WITH', 'c.id = p.idCategory')
            ->groupBy('c.namecategory')
            ->orderBy('sum(p.likes)', 'DESC')
            ->getQuery()
            ->getResult();
        ;
    }

    // /**
    //  * @return Posts[] Returns an array of Posts objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Posts
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
