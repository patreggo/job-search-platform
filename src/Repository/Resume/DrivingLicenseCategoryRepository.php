<?php

namespace App\Repository\Resume;

use App\Entity\Resume\DrivingLicenseCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DrivingLicenseCategory>
 *
 * @method DrivingLicenseCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method DrivingLicenseCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method DrivingLicenseCategory[]    findAll()
 * @method DrivingLicenseCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DrivingLicenseCategoryRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DrivingLicenseCategory::class);
    }

    /**
     * @param DrivingLicenseCategory $entity
     * @param bool $flush
     * @return void
     */
    public function save(DrivingLicenseCategory $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param DrivingLicenseCategory $entity
     * @param bool $flush
     * @return void
     */
    public function remove(DrivingLicenseCategory $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return DrivingLicenseCategory[] Returns an array of DrivingLicenseCategory objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?DrivingLicenseCategory
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
