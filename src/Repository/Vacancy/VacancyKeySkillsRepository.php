<?php

namespace App\Repository\Vacancy;

use App\Entity\Vacancy\VacancyKeySkills;
use Common\Repository\AbstractRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<VacancyKeySkills>
 *
 * @method VacancyKeySkills|null find($id, $lockMode = null, $lockVersion = null)
 * @method VacancyKeySkills|null findOneBy(array $criteria, array $orderBy = null)
 * @method VacancyKeySkills[]    findAll()
 * @method VacancyKeySkills[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VacancyKeySkillsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VacancyKeySkills::class);
    }

    public function save(VacancyKeySkills $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(VacancyKeySkills $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return VacancyKeySkills[] Returns an array of VacancyKeySkills objects
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

//    public function findOneBySomeField($value): ?VacancyKeySkills
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
