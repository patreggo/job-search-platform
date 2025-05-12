<?php

namespace App\Repository\Resume;

use App\Entity\Resume\WorkPlace;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends AbstractRepository<WorkPlace>
 *
 * @method WorkPlace|null find($id, $lockMode = null, $lockVersion = null)
 * @method WorkPlace|null findOneBy(array $criteria, array $orderBy = null)
 * @method WorkPlace[]    findAll()
 * @method WorkPlace[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WorkPlaceRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WorkPlace::class);
    }

    /**
     * @param WorkPlace $entity
     * @param bool $flush
     * @return void
     */
    public function save(WorkPlace $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param WorkPlace $entity
     * @param bool $flush
     * @return void
     */
    public function remove(WorkPlace $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return WorkPlace[] Returns an array of WorkPlace objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('w')
//            ->andWhere('w.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('w.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?WorkPlace
//    {
//        return $this->createQueryBuilder('w')
//            ->andWhere('w.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
