<?php

namespace App\Repository\Resume;

use App\Entity\Resume\AwardAndAchievement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AwardAndAchievement>
 *
 * @method AwardAndAchievement|null find($id, $lockMode = null, $lockVersion = null)
 * @method AwardAndAchievement|null findOneBy(array $criteria, array $orderBy = null)
 * @method AwardAndAchievement[]    findAll()
 * @method AwardAndAchievement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AwardAndAchievementRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AwardAndAchievement::class);
    }

    /**
     * @param AwardAndAchievement $entity
     * @param bool $flush
     * @return void
     */
    public function save(AwardAndAchievement $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param AwardAndAchievement $entity
     * @param bool $flush
     * @return void
     */
    public function remove(AwardAndAchievement $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return AwardsAndAchievements[] Returns an array of AwardsAndAchievements objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?AwardsAndAchievements
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
