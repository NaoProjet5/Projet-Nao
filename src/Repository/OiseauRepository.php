<?php

namespace App\Repository;

use App\Entity\Oiseau;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Oiseau|null find($id, $lockMode = null, $lockVersion = null)
 * @method Oiseau|null findOneBy(array $criteria, array $orderBy = null)
 * @method Oiseau[]    findAll()
 * @method Oiseau[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OiseauRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Oiseau::class);
    }
    public function name_bird(){
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery('SELECT oi.lbNom FROM App\Entity\Oiseau oi');
        $data = $query->getResult();
        return $data;
    }
    public function nameLike($q, int $limit = 10){
        $entityManager = $this->getEntityManager();
        $dql_query = $entityManager->createQuery("
    SELECT o.lbNom FROM App\Entity\Oiseau o
    WHERE 
      o.lbNom LIKE :key
");
        $dql_query->setParameter('key', '%'.$q.'%');
        $dql_query->setMaxResults($limit);
        $data = $dql_query->getResult();
        return $data;
    }
    public function findAllMatching(string $query, int $limit = 10)
    {
        return $this->createQueryBuilder('o.lbNom')
            ->andWhere('o.lbNom LIKE :query')
            ->setParameter('query', '%'.$query.'%')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
    public function findLimitBird($value)
    {
        return $this->createQueryBuilder('o')
            ->setMaxResults($value)
            ->getQuery()
            ->getResult()
            ;
    }

//    /**
//     * @return Oiseau[] Returns an array of Oiseau objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Oiseau
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
