<?php

namespace App\Repository\Vacancy;

use App\Entity\Vacancy\VacancyWorkExperience;
use Common\Repository\AbstractRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<VacancyWorkExperience>
 *
 * @method VacancyWorkExperience|null find($id, $lockMode = null, $lockVersion = null)
 * @method VacancyWorkExperience|null findOneBy(array $criteria, array $orderBy = null)
 * @method VacancyWorkExperience[]    findAll()
 * @method VacancyWorkExperience[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VacancyWorkExperienceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VacancyWorkExperience::class);
    }

    public function save(VacancyWorkExperience $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(VacancyWorkExperience $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return VacancyWorkExperience[] Returns an array of VacancyWorkExperience objects
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

//    public function findOneBySomeField($value): ?VacancyWorkExperience
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
