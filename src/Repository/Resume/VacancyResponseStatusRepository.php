<?php

namespace App\Repository\Resume;

use App\Entity\Resume\VacancyResponseStatus;
use Common\Repository\AbstractRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends AbstractRepository<VacancyResponseStatus>
 *
 * @method VacancyResponseStatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method VacancyResponseStatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method VacancyResponseStatus[]    findAll()
 * @method VacancyResponseStatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VacancyResponseStatusRepository extends AbstractRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VacancyResponseStatus::class);
    }

    /**
     * @param VacancyResponseStatus $entity
     * @param bool $flush
     * @return void
     */
    public function save(VacancyResponseStatus $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param VacancyResponseStatus $entity
     * @param bool $flush
     * @return void
     */
    public function remove(VacancyResponseStatus $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return QueryBuilder
     */
    public function getSelectableStatusesQB(): QueryBuilder
    {
        return $this->createQueryBuilder('vrs')
            ->where('vrs.techName != :defaultStatus')
            ->setParameter('defaultStatus', VacancyResponseStatus::DEFAULT_STATUS_TECH_NAME);
    }

    /**
     * @return float|int|mixed|string
     */
    public function findSelectableStatuses(): mixed
    {
        return $this->getSelectableStatusesQB()->getQuery()->getResult();
    }

//    /**
//     * @return VacancyResponseStatus[] Returns an array of VacancyResponseStatus objects
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

//    public function findOneBySomeField($value): ?VacancyResponseStatus
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
