<?php

namespace App\Repository;

use App\Entity\City;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends AbstractRepository<City>
 *
 * @method City|null find($id, $lockMode = null, $lockVersion = null)
 * @method City|null findOneBy(array $criteria, array $orderBy = null)
 * @method City[]    findAll()
 * @method City[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CityRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, City::class);
    }

    /**
     * @param City $entity
     * @param bool $flush
     * @return void
     */
    public function save(City $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param City $entity
     * @param bool $flush
     * @return void
     */
    public function remove(City $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param string $alias
     * @return mixed
     */
    public function findByAlias(string $alias): mixed
    {
        $qb = $this->createQueryBuilder('c');

        $qb->innerJoin('c.cityAliases', 'al', 'WITH', 'al.name LIKE LOWER(:aliasName)')
           ->setParameter('aliasName', '%' . $alias . '%');

        return $qb
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param int $osmId
     * @return int
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function countCityByOsmId(int $osmId): int
    {
        return $this->createQueryBuilder('c')
                    ->where('c.osmId = :osmId')
                    ->setParameter('osmId', $osmId)
                    ->select('count(c.id)')
                    ->getQuery()
                    ->getSingleScalarResult();
    }
}
