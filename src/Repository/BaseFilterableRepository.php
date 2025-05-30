<?php

namespace App\Repository;

use App\Service\FilterService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

abstract class BaseFilterableRepository extends ServiceEntityRepository
{
    protected FilterService $filterService;

    public function __construct(
        ManagerRegistry $registry,
        string $entityClass,
        FilterService $filterService,
    ) {
        parent::__construct($registry, $entityClass);
        $this->filterService = $filterService;
    }

    public function findByFilters(array $filters): array
    {
        $qb = $this->createQueryBuilder($this->getAlias());

        // Применяем фильтры
        $this->filterService->applyFilters($qb, $filters, $this->getEntityName());

        // Применяем пагинацию
        $this->applyPagination($qb, $filters);

        return $qb->getQuery()->getResult();
    }

    public function countByFilters(array $filters): int
    {
        $qb = $this->createQueryBuilder($this->getAlias())
            ->select('COUNT(' . $this->getAlias() . '.id)');

        $this->filterService->applyFilters($qb, $filters, $this->getEntityName());

        return $qb->getQuery()->getSingleScalarResult();
    }

    protected function applyPagination($qb, array $filters): void
    {
        $page = max(1, (int)($filters['page'] ?? 1));
        $limit = min(100, max(1, (int)($filters['limit'] ?? 20)));
        $offset = ($page - 1) * $limit;

        $qb->setFirstResult($offset)
            ->setMaxResults($limit);
    }

    abstract protected function getAlias(): string;
}