<?php

namespace App\Repository\Vacancy;

use App\Entity\Vacancy\VacancyIncomePayment;
use Common\Repository\AbstractRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<VacancyIncomePayment>
 *
 * @method VacancyIncomePayment|null find($id, $lockMode = null, $lockVersion = null)
 * @method VacancyIncomePayment|null findOneBy(array $criteria, array $orderBy = null)
 * @method VacancyIncomePayment[]    findAll()
 * @method VacancyIncomePayment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VacancyIncomePaymentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VacancyIncomePayment::class);
    }

    public function save(VacancyIncomePayment $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(VacancyIncomePayment $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return VacancyIncomePayment[] Returns an array of VacancyIncomePayment objects
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

//    public function findOneBySomeField($value): ?VacancyIncomePayment
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
