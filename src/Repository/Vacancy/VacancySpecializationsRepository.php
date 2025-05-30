<?php

namespace App\Repository\Vacancy;

use App\Entity\Vacancy\VacancySpecializations;
use App\Repository\BaseFilterableRepository;
use App\Service\FilterService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<VacancySpecializations>
 *
 * @method VacancySpecializations|null find($id, $lockMode = null, $lockVersion = null)
 * @method VacancySpecializations|null findOneBy(array $criteria, array $orderBy = null)
 * @method VacancySpecializations[]    findAll()
 * @method VacancySpecializations[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VacancySpecializationsRepository extends BaseFilterableRepository
{
    public function __construct(ManagerRegistry $registry, FilterService $filterService)
    {
        parent::__construct($registry, VacancySpecializations::class, $filterService );
    }

    protected function getAlias(): string
    {
        return 's';
    }


    public function save(VacancySpecializations $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(VacancySpecializations $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return VacancySpecializations[] Returns an array of VacancySpecializations objects
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

//    public function findOneBySomeField($value): ?VacancySpecializations
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
