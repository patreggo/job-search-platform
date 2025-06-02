<?php

namespace App\Repository\Resume;

use App\Entity\Resume\Resume;
use App\Entity\User;
use App\Entity\Vacancy\Vacancy;
use App\Repository\BaseFilterableRepository;
use App\Service\FilterService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends AbstractRepository<Resume>
 *
 * @method Resume|null find($id, $lockMode = null, $lockVersion = null)
 * @method Resume|null findOneBy(array $criteria, array $orderBy = null)
 * @method Resume[]    findAll()
 * @method Resume[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResumeRepository extends BaseFilterableRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry, FilterService $filterService)
    {
        parent::__construct($registry, Resume::class, $filterService);
    }

    protected function getAlias(): string
    {
        return 'r';
    }

    /**
     * @param Resume $entity
     * @param bool $flush
     * @return void
     */
    public function save(Resume $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param Resume $entity
     * @param bool $flush
     * @return void
     */
    public function remove(Resume $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param User|null $user
     * @return QueryBuilder
     */
    public function prepareQBForFilter(?User $user = null): QueryBuilder
    {
        $qb = $this->createQueryBuilder('r');

        if ($user !== null) {
            $qb
                ->join('r.user', 'ru')
                ->andWhere('ru.id = :rUser')
                ->setParameter(':rUser', $user)
            ;
        }

        return $qb;
    }

    /**
     * @param User $user
     * @param Vacancy $vacancy
     * @return float|int|mixed|string
     */
    public function findUserResumesRespondedVacancy(User $user, Vacancy $vacancy): mixed
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.user = :user')
            ->innerJoin('r.vacancyResponses', 'rvr')
            ->andWhere('rvr.vacancy = :vacancy')
            ->setParameters(new ArrayCollection([
                new Parameter('user', $user),
                new Parameter('vacancy', $vacancy)
            ]))
            ->getQuery()->getResult();
    }

    /**
     * @param User $user
     * @return QueryBuilder
     */
    public function getResumeFavoriteQBByUser(
        User $user,
    ): QueryBuilder
    {
        return
            $this->createQueryBuilder('r')
                ->andWhere(':fu MEMBER OF r.favoritesUsers')
                ->setParameter('fu', [$user->getId()])
            ;
    }

    /**
     * @param User $user
     * @param int $page
     * @param int $postsPerPage
     * @return Paginator
     */
    public function getResumeFavoritePaginatorByUser(
        User $user,
        int $page,
        int $postsPerPage
    ): Paginator
    {
        return $this->prepareApiPaginatorForQuery($this->getResumeFavoriteQBByUser($user), $page, $postsPerPage);
    }
}
