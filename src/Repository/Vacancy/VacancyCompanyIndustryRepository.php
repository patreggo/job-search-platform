<?php

namespace App\Repository\Vacancy;

use App\Entity\Vacancy\VacancyCompanyIndustry;
use Common\Repository\AbstractRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<VacancyCompanyIndustry>
 *
 * @method VacancyCompanyIndustry|null find($id, $lockMode = null, $lockVersion = null)
 * @method VacancyCompanyIndustry|null findOneBy(array $criteria, array $orderBy = null)
 * @method VacancyCompanyIndustry[]    findAll()
 * @method VacancyCompanyIndustry[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VacancyCompanyIndustryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VacancyCompanyIndustry::class);
    }

    public function save(VacancyCompanyIndustry $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(VacancyCompanyIndustry $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return VacancyCompanyIndustry[] Returns an array of VacancyCompanyIndustry objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('v.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?VacancyCompanyIndustry
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
