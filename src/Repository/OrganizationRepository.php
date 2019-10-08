<?php

namespace App\Repository;

use App\Entity\BelongTo;
use App\Entity\Organization;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Organization|null find($id, $lockMode = null, $lockVersion = null)
 * @method Organization|null findOneBy(array $criteria, array $orderBy = null)
 * @method Organization[]    findAll()
 * @method Organization[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrganizationRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Organization::class);
    }

    public function findOrganizationWithUser(int $id)
    {
        return $this->createQueryBuilder('o')
            ->select('o.id')
            //  ->addSelect('o.name')
            ->andWhere('o.user = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
    }

    public function findOrganizationsNotAdmin($user)
    {
        return $this->createQueryBuilder('org')
            ->where('org.user != :user')
            ->setParameter('user', $user)
            ->leftJoin('org.belongTos', 'bto')
            ->from(BelongTo::class, 'b')
            ->andWhere('b.isAdmin = 0')
            ->addSelect('o.user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

    public function findOrganizationWithoutUser(int $id)
    {
        return $this->createQueryBuilder('o')
            ->select('o.id, o.name')
            //  ->addSelect('o.name')
            ->andWhere('o.user != :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
    }

    public function withoutOrg(int $id)
    {
        return $this->createQueryBuilder('o')
            ->select('o.id, o.name')
            ->andWhere('o.id != :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
    }
    /*
    $qb = $this->createQueryBuilder('o');
    $qb->select('o.name')
        ->addSelect('o.id AS organization_id')
        ->addSelect('u.id AS user_id')
        ->innerJoin('o.user', 'u')
        ->where('o.id = :id')
        ->setParameter('id', $id);

    $qb->getQuery()->getResult();
    */


    // /**
    //  * @return Organization[] Returns an array of Organization objects
    //  */

    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.id= :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult();
    }

    public function findAllInformationsOrganizationWithUser(int $id)
    {
        return $this->createQueryBuilder('o')
            ->select('o.id')
            ->addSelect('o.name, o.organization_number, o.email, o.address_headquarters, o.zip_code, o.city, o.president_lastname, o.president_firstname, o.phone_fix, o.phone_mobile, o.logo')
            ->andWhere('o.user = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
    }

    /*
    public function findOneBySomeField($value): ?Organization
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
