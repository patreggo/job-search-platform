<?php

namespace App\Repository;

use App\Entity\User;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use function get_class;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function save(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        $user->setPassword($newHashedPassword);

        $this->save($user, true);
    }


    /**
     * @param DateTimeInterface $date
     * @return array
     * @throws Exception
     */
    public function findByDate(DateTimeInterface $date): array
    {
        $from = new DateTimeImmutable($date->format("Y-m-d") . "00:00:00");
        $to = new DateTimeImmutable($date->format("Y-m-d") . "23:59:59");

        return $this->createQueryBuilder('u')
            ->andWhere('u.createdAt BETWEEN :from AND :to')
            ->setParameter('from', $from)
            ->setParameter('to', $to)
            ->orderBy('u.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param User|null $user
     * @param int $limit
     * @return array
     */
    public function findUserByOffsetAndLimit(?User $user, int $limit): array
    {
        $qb = $this
            ->createQueryBuilder('u')
            ->setMaxResults($limit);

        if ($user !== null) {
            $qb->andWhere('u.id > :firstUser')
                ->setParameter('firstUser', $user);
        }

        return $qb->getQuery()
            ->getResult();
    }

    /**
     * @param int $limit
     * @return array
     */
    public function findFirstUserByLimit(int $limit): array
    {
        return $this
            ->createQueryBuilder('u')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param User $user
     * @param int $page
     * @param int $postsPerPage
     * @return Paginator
     */
    public function getUserFavoritePaginator(
        User $user,
        int  $page,
        int  $postsPerPage
    ): Paginator
    {
        return $this->prepareApiPaginatorForQuery(
            $this
                ->createQueryBuilder('u')
                ->join('u.favoritesBy', 'f')
                ->where('f.id = :user')
                ->setParameter('user', $user->getId()),
            $page,
            $postsPerPage
        );
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function checkSubscriptionByUser(User $seller, User $authorizedUser ):bool
    {
        return $this
            ->createQueryBuilder('u')
            ->select('count(u.id)')
            ->where('u.id = :authorizedUserId')
            ->andWhere(':sellerId MEMBER OF u.favoriteUsers')
            ->setParameter('authorizedUserId', $authorizedUser->getId())
            ->setParameter('sellerId', $seller->getId())
            ->getQuery()
            ->getSingleScalarResult();
    }
}
