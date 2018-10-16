<?php

namespace App\Repository;

use App\Entity\LwArticle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method LwArticle|null find($id, $lockMode = null, $lockVersion = null)
 * @method LwArticle|null findOneBy(array $criteria, array $orderBy = null)
 * @method LwArticle[]    findAll()
 * @method LwArticle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LwArticleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, LwArticle::class);
    }

//    /**
//     * @return LwArticle[] Returns an array of LwArticle objects
//     */
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
    public function findOneBySomeField($value): ?LwArticle
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
