<?php

namespace App\Repository\Vacancy;

use App\Entity\Vacancy\VacancyIncomePaymentPeriod;
use Common\Repository\AbstractRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends AbstractRepository<VacancyIncomePaymentPeriod>
 *
 * @method VacancyIncomePaymentPeriod|null find($id, $lockMode = null, $lockVersion = null)
 * @method VacancyIncomePaymentPeriod|null findOneBy(array $criteria, array $orderBy = null)
 * @method VacancyIncomePaymentPeriod[]    findAll()
 * @method VacancyIncomePaymentPeriod[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VacancyIncomePaymentPeriodRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VacancyIncomePaymentPeriod::class);
    }

    public function save(VacancyIncomePaymentPeriod $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(VacancyIncomePaymentPeriod $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return VacancyIncomePaymentPeriod[] Returns an array of VacancyIncomePaymentPeriod objects
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

//    public function findOneBySomeField($value): ?VacancyIncomePaymentPeriod
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
