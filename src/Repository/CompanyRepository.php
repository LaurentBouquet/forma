<?php

namespace App\Repository;

use App\Entity\Company;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Company|null find($id, $lockMode = null, $lockVersion = null)
 * @method Company|null findOneBy(array $criteria, array $orderBy = null)
 * @method Company[]    findAll()
 * @method Company[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompanyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Company::class);
    }

    // /**
    //  * @return Company[] Returns an array of Company objects
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

    public function findOneById($id): ?Company
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.id = :val')
            ->setParameter('val', $id)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findSameCompany($corporateName,$city)
    {
        $qb = $this->createQueryBuilder('c')
            ->where('c.corporate_name = :val1')
            ->setParameter('val1', $corporateName)
            ->andWhere('c.city = :val2')
            ->setParameter('val2', $city);
            
        $query = $qb->getQuery();

        return $query->execute();
    }

    public function getIdCompany($value)
    {
        $qb = $this->createQueryBuilder('c')
            ->where('c.corporate_name = :val')
            ->setParameter('val', $value);
            
        $query = $qb->getQuery();

        return $query->execute();
    }
}
