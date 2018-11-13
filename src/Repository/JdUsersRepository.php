<?php

namespace App\Repository;

use App\Entity\JdUsers;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method JdUsers|null find($id, $lockMode = null, $lockVersion = null)
 * @method JdUsers|null findOneBy(array $criteria, array $orderBy = null)
 * @method JdUsers[]    findAll()
 * @method JdUsers[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JdUsersRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, JdUsers::class);
    }
    public function getTreeUsers()
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT u
            FROM App\Entity\JdUsers u
            ORDER BY u.createdAt DESC'
        )->setMaxResults(3);
        // returns an array of users objects
        return $query->execute();
    }

//    /**
//     * @return JdUsers[] Returns an array of JdUsers objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('j.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?JdUsers
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
