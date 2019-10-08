<?php

namespace App\Repository;

use App\Entity\BelongTo;
use App\Entity\Organization;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method BelongTo|null find($id, $lockMode = null, $lockVersion = null)
 * @method BelongTo|null findOneBy(array $criteria, array $orderBy = null)
 * @method BelongTo[]    findAll()
 * @method BelongTo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BelongToRepository extends ServiceEntityRepository
{

    public function __construct(RegistryInterface $registry)
    {

        parent::__construct($registry, BelongTo::class);
    }




    public function findByUserIsNotAdmin($id)
    {
        return $this->createQueryBuilder('bto')
            ->andWhere('bto.organizations = :organizations')
            ->andWhere('bto.isAdmin = 0')
            ->setParameter('organizations', $id)
            ->join('bto.organizations', 'org')
            ->select('bto.id')
            ->addSelect('u.firstname, u.lastname')
            ->innerJoin('bto.user', 'u')
            ->getQuery()
            ->getResult();
    }

    public function findBelongToIdWhithUser($id)
    {
        return $this->createQueryBuilder('bto')
            ->leftJoin('bto.organizations', 'org')
            ->from(Organization::class, 'o')
            ->addSelect('org.id, org.name')
            ->andWhere('bto.user = :user')
           // ->addSelect('bto.isAdmin')
            ->andWhere('bto.isAdmin = 1')
            ->setParameter('user', $id)
            ->getQuery()
            ->getResult();
    }

    public function findBelongToIdWhithUserNotAdmin($id)
    {
        return $this->createQueryBuilder('bto')
            ->leftJoin('bto.organizations', 'org')
            ->from(Organization::class, 'o')
            ->addSelect('org.id, org.name')
            ->andWhere('bto.user = :user')
           // ->addSelect('bto.isAdmin')
            ->andWhere('bto.isAdmin = 0')
            ->setParameter('user', $id)
            ->getQuery()
            ->getResult();
    }

    public function findBelongToIdWhithoutUserAdmin($id)
    {
        return $this->createQueryBuilder('bto')
            ->leftJoin('bto.organizations', 'org')
            ->from(Organization::class, 'o')
            ->addSelect('org.id, org.name')
            ->andWhere('bto.user = :user')
            ->addSelect('bto.isAdmin')
            ->andWhere('bto.isAdmin = 1')
            ->setParameter('user', $id)
            ->getQuery()
            ->getResult();
    }

    public function findOrganizationsNotAdmin($id){
        return $this->createQueryBuilder('bto')
            ->leftJoin('bto.organizations', 'org')
            ->from(Organization::class, 'o')
            ->addSelect('org.id, org.name')
           // ->andWhere('bto.user = :user')
            ->addSelect('o.user')
            ->andWhere('o.user != :user')
            //->setParameter('user', $id)
            //->addSelect('bto.isAdmin')
           // ->andWhere('bto.isAdmin = 0')
            ->setParameter('user', $id)
            ->getQuery()
            ->getResult();
    }
    /*
   public function findByOrganization($organization_id)
   {
       return $this->createQueryBuilder('bto')
           ->andWhere('bto.organizations = :organizations')
           ->andWhere('bto.isAdmin = 0')
           ->setParameter('organizations', $organization_id)
           ->join('bto.organizations', 'org')
           ->select('bto.id')
           ->addSelect('u.firstname, u.lastname')
           ->innerJoin('bto.user', 'u')
           ->getQuery()
           ->getResult();
   }
*/
    /*
   public function findByUserIsAdmin($userId)
   {
       return $this->createQueryBuilder('bto')
           ->select('bto.isAdmin')
           ->andWhere('bto.user = :user')
           ->setParameter('user', $userId)
           ->getQuery()
           ->getResult();
   }
*/
    /*
    public function findOrganizationWithId($user_id){
        return $this->createQueryBuilder('bto')
            ->select('bto.organizations')
            ->andWhere('bto.organizations = :organizations')
            ->join('bto.organizations', 'o')
            ->addSelect('o.name, o.id')
            ->andWhere('o.id = :id')
            ->setParameter('id', $user_id)
            ->getQuery()
            ->getResult();
    }*/
    /*
     * SELECT * FROM `belong_to`, organization WHERE organization.id = belong_to.organization_id AND belong_to.user_id = 53
     */
    /*
    public function findByIsAdmin($id)
    {
        return $this->createQueryBuilder('bto')
            ->andWhere('bto.user = :user')
            ->andWhere('bto.isAdmin = 1')
            ->setParameter('user', $id)
            ->join('bto.user', 'u')
            ->select('bto.id')
            ->addSelect('o.id')
            ->innerJoin('bto.organizations', 'o')
            ->getQuery()
            ->getResult();
    }
*/
}

