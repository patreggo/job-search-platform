<?php

namespace App\Repository\Resume;

use App\Entity\Resume\Resume;
use App\Entity\Resume\VacancyResponse;
use App\Entity\Resume\VacancyResponseStatus;
use App\Entity\User;
use App\Entity\Vacancy\Vacancy;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends AbstractRepository<VacancyResponse>
 *
 * @method VacancyResponse|null find($id, $lockMode = null, $lockVersion = null)
 * @method VacancyResponse|null findOneBy(array $criteria, array $orderBy = null)
 * @method VacancyResponse[]    findAll()
 * @method VacancyResponse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VacancyResponseRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VacancyResponse::class);
    }

    /**
     * @param VacancyResponse $entity
     * @param bool $flush
     * @return void
     */
    public function save(VacancyResponse $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param VacancyResponse $entity
     * @param bool $flush
     * @return void
     */
    public function remove(VacancyResponse $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param User $user
     * @param int $page
     * @param int $perPage
     * @param VacancyResponseStatus|null $vacancyResponseStatus
     * @return Paginator
     */
    public function findUserResponsesByStatus(
        User $user,
        int $page,
        int $perPage,
        ?VacancyResponseStatus $vacancyResponseStatus = null
    ): Paginator
    {
        $qb = $this->createQueryBuilder('vr')
            ->innerJoin('vr.resume', 'vrr')
            ->andWhere('vrr.user = :user')
            ->setParameter('user', $user);

        if ($vacancyResponseStatus) {
            $qb
                ->innerJoin('vr.status', 'vrs')
                ->andWhere('vrs.techName = :status')
                ->setParameter('status', $vacancyResponseStatus->getTechName());
        }
        return $this->prepareApiPaginatorForQuery($qb->getQuery(), $page, $perPage);
    }

    /**
     * @param Resume $resume
     * @param int $page
     * @param int $postsPerPage
     * @return Paginator
     */
    public function findResponsesByResume(
        Resume $resume,
        int $page,
        int $postsPerPage,
        ?User $user,
        ?bool $initiator
    ): Paginator
    {
        $qb = $this->createQueryBuilder('vr')
            ->andWhere('vr.resume = :resume')
            ->setParameter('resume', $resume)
            ;

        if ($initiator !== null){
            $whereExpr = $initiator ? 'eq': 'neq';

            $qb
                ->andWhere($qb->expr()->$whereExpr('vr.initiator', ':user'))
                ->setParameter('user', $user)
            ;
        }

        return $this->prepareApiPaginatorForQuery($qb, $page, $postsPerPage);
    }

    /**
     * @param Vacancy $vacancy
     * @param int $page
     * @param int $perPage
     * @param User|null $user
     * @param bool|null $initiator
     * @return Paginator
     */
    public function findResponsesByVacancy(
        Vacancy $vacancy,
        int $page,
        int $perPage,
        ?User $user,
        ?bool $initiator
    ): Paginator
    {
        $qb = $this->createQueryBuilder('vr')
            ->andWhere('vr.vacancy = :vacancy')
            ->setParameter('vacancy', $vacancy);
        if ($initiator !== null){
            $whereExpr = $initiator ? 'eq': 'neq';

            $qb
                ->andWhere($qb->expr()->$whereExpr('vr.initiator', ':user'))
                ->setParameter('user', $user)
            ;
        }
        return $this->prepareApiPaginatorForQuery($qb, $page, $perPage);
    }

//    /**
//     * @return VacancyResponse[] Returns an array of VacancyResponse objects
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

//    public function findOneBySomeField($value): ?VacancyResponse
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
