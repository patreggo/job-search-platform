<?php

namespace App\Repository\Vacancy;

use App\Entity\Vacancy\VacancyEmploymentType;
use App\Repository\BaseFilterableRepository;
use App\Service\FilterService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<VacancyEmploymentType>
 *
 * @method VacancyEmploymentType|null find($id, $lockMode = null, $lockVersion = null)
 * @method VacancyEmploymentType|null findOneBy(array $criteria, array $orderBy = null)
 * @method VacancyEmploymentType[]    findAll()
 * @method VacancyEmploymentType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VacancyEmploymentTypeRepository extends BaseFilterableRepository
{
    public function __construct(ManagerRegistry $registry, FilterService $filterService)
    {
        parent::__construct($registry, VacancyEmploymentType::class, $filterService );
    }

    protected function getAlias(): string
    {
        return 'et';
    }

    public function save(VacancyEmploymentType $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(VacancyEmploymentType $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return VacancyEmploymentType[] Returns an array of VacancyEmploymentType objects
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

//    public function findOneBySomeField($value): ?VacancyEmploymentType
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
