<?php

namespace App\Repository\Vacancy;

use App\Entity\Vacancy\VacancyRegistrationMethods;
use Common\Repository\AbstractRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends AbstractRepository<VacancyRegistrationMethods>
 *
 * @method VacancyRegistrationMethods|null find($id, $lockMode = null, $lockVersion = null)
 * @method VacancyRegistrationMethods|null findOneBy(array $criteria, array $orderBy = null)
 * @method VacancyRegistrationMethods[]    findAll()
 * @method VacancyRegistrationMethods[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VacancyRegistrationMethodsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VacancyRegistrationMethods::class);
    }

    public function save(VacancyRegistrationMethods $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(VacancyRegistrationMethods $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return VacancySpecializations[] Returns an array of VacancyRegistrationMethods objects
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

//    public function findOneBySomeField($value): ?VacancyRegistrationMethods
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
