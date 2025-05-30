<?php

namespace App\Repository\Vacancy;

use App\Entity\Company;
use App\Entity\Resume\Resume;
use App\Entity\User;
use App\Entity\Vacancy\Vacancy;
use App\Repository\BaseFilterableRepository;
use App\Service\FilterService;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Vacancy|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vacancy|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vacancy[]    findAll()
 * @method Vacancy[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VacancyRepository extends BaseFilterableRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry, FilterService $filterService,)
    {
        parent::__construct($registry, Vacancy::class, $filterService );
    }

    protected function getAlias(): string
    {
        return 'v';
    }

    /**
     * @param Vacancy $entity
     * @param bool $flush
     * @return void
     */
    public function save(Vacancy $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param Vacancy $entity
     * @param bool $flush
     * @return void
     */
    public function remove(Vacancy $entity, bool $flush = false): void
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
        $qb = $this->createQueryBuilder('v');

        $subQuery = $this->getEntityManager()->createQueryBuilder()
            ->select('c.id')
            ->from(Company::class, 'c')
            ->andWhere('c.user = :user')
            ->getDQL();

        if ($user !== null) {
            $qb
                ->join('v.company', 'vc')
                ->andWhere('vc.id IN ( ' . $subQuery . ')')
                ->setParameter('user', $user)
            ;
        }
        return $qb;
    }

    /**
     * @param User $user
     * @param Resume $resume
     * @return mixed
     */
    public function findUserVacanciesRespondedResume(User $user, Resume $resume): mixed
    {
        return $this->prepareUserVacanciesWithVacancyResponseQB($user)
            ->andWhere('vr.resume = :resume')
            ->setParameter('resume', $resume)
            ->getQuery()->getResult();
    }

    /**
     * @param User $user
     * @return mixed
     */
    public function findUserRespondedVacancies(User $user): mixed
    {
        return $this->prepareUserVacanciesWithVacancyResponseQB($user)
            ->getQuery()->getResult();
    }

    /**
     * @param User $user
     * @return QueryBuilder
     */
    private function prepareUserVacanciesWithVacancyResponseQB(User $user): mixed
    {
        return $this->createQueryBuilder('v')
            ->innerJoin('v.company', 'vc')
            ->andWhere('vc.user = :user')
            ->innerJoin('v.vacancyResponses', 'vr')
            ->setParameter('user', $user)
        ;
    }

    /**
     * @param User $user
     * @return QueryBuilder
     */
    public function getVacancyFavoriteQBByUser(
        User $user,
    ): QueryBuilder
    {
        return
            $this->createQueryBuilder('v')
                ->andWhere(':fu MEMBER OF v.favoritesUsers')
                ->setParameter('fu', [$user->getId()])
            ;
    }

    /**
     * @param User $user
     * @param int $page
     * @param int $postsPerPage
     * @return Paginator
     */
    public function getVacancyFavoritePaginatorByUser(
        User $user,
        int $page,
        int $postsPerPage
    ): Paginator
    {
        return $this->prepareApiPaginatorForQuery($this->getVacancyFavoriteQBByUser($user), $page, $postsPerPage);
    }
}
