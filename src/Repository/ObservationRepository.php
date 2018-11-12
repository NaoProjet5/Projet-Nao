<?php

namespace App\Repository;

use App\Entity\Observation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Observation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Observation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Observation[]    findAll()
 * @method Observation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ObservationRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Observation::class);
    }

    public function getFiveObservation()
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT o
            FROM App\Entity\Observation o
            WHERE o.valide = 1
            ORDER BY o.createdAt DESC'
        )->setMaxResults(5);
        // returns an array of articles objects
        return $query->execute();
    }


    public function getMyOwneObservation($id_user): array
    {
        // automatically knows to select Products
        // the "p" is an alias you'll use in the rest of the query
        $qb = $this->createQueryBuilder('o')
            ->andWhere('o.user = :id_user')
            ->setParameter('id_user', $id_user)
            ->getQuery();

        return $qb->execute();

    }
    public function getGpsData($oiseau){
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery('SELECT ob FROM App\Entity\Observation ob JOIN ob.oiseau oi WHERE ob.valide = :valide AND oi.lbNom = :oiseau');
        $query->setParameters(array(
            'valide' => 1,
            'oiseau' => $oiseau
        ));
        $data = $query->getResult();
        return $data;
    }


//    /**
//     * @return Observation[] Returns an array of Observation objects
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
    public function findOneBySomeField($value): ?Observation
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
