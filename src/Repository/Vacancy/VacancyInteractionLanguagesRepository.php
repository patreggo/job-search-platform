<?php

namespace App\Repository\Vacancy;

use App\Entity\Vacancy\VacancyInteractionLanguages;
use Common\Repository\AbstractRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<VacancyInteractionLanguages>
 *
 * @method VacancyInteractionLanguages|null find($id, $lockMode = null, $lockVersion = null)
 * @method VacancyInteractionLanguages|null findOneBy(array $criteria, array $orderBy = null)
 * @method VacancyInteractionLanguages[]    findAll()
 * @method VacancyInteractionLanguages[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VacancyInteractionLanguagesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VacancyInteractionLanguages::class);
    }

    public function save(VacancyInteractionLanguages $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(VacancyInteractionLanguages $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return VacancyInteractionLanguages[] Returns an array of VacancyInteractionLanguages objects
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

//    public function findOneBySomeField($value): ?VacancyInteractionLanguages
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
