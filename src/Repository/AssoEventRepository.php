<?php

namespace App\Repository;

use App\Entity\AssoEvent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method AssoEvent|null find($id, $lockMode = null, $lockVersion = null)
 * @method AssoEvent|null findOneBy(array $criteria, array $orderBy = null)
 * @method AssoEvent[]    findAll()
 * @method AssoEvent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AssoEventRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AssoEvent::class);
    }

    /**
      * @return AssoEvent[] Returns an array of AssoEvent objects
      */
    public function findEventsWithOrganizations($organization)
    {
        return $this->createQueryBuilder('a')
            ->select('a.id, a.name, a.description, a.beginAt, a.endAt, a.eventPictureOne, a.eventPictureTwo, a.eventPictureThree')
            ->innerJoin('a.organization', 'o')
            ->andWhere('o.id = :organization')
            ->setParameter('organization', $organization)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    public function findEventsWithUser(int $id)
    {
        return $this->createQueryBuilder('a')
            ->select('a.id')
            ->addSelect('a.name, a.description, a.beginAt, a.endAt')
            ->andWhere('a.user = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
    }


    // /**
    //  * @return AssoEvent[] Returns an array of AssoEvent objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AssoEvent
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
