<?php

namespace App\Repository\Vacancy;

use App\Entity\Vacancy\VacancyCommunicationType;
use Common\Repository\AbstractRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<VacancyCommunicationType>
 *
 * @method VacancyCommunicationType|null find($id, $lockMode = null, $lockVersion = null)
 * @method VacancyCommunicationType|null findOneBy(array $criteria, array $orderBy = null)
 * @method VacancyCommunicationType[]    findAll()
 * @method VacancyCommunicationType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VacancyCommunicationTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VacancyCommunicationType::class);
    }

    public function save(VacancyCommunicationType $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(VacancyCommunicationType $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return VacancyCommunicationType[] Returns an array of VacancyCommunicationType objects
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

//    public function findOneBySomeField($value): ?VacancyCommunicationType
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
