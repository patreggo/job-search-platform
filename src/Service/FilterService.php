<?php

namespace App\Service;

use App\Attribute\Filterable;
use App\Enum\FilterType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use ReflectionClass;
use Symfony\Component\HttpFoundation\Request;

class FilterService
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    public function parseFilters(Request $request): array
    {
        $filters = [];

        foreach ($request->query->all() as $key => $value) {
            if ($this->isValidFilterValue($value)) {
                $filters[$key] = $this->parseFilterValue($value);
            }
        }

        return $filters;
    }

    public function applyFilters(QueryBuilder $qb, array $filters, string $entityClass): QueryBuilder
    {
        $reflection = new ReflectionClass($entityClass);
        $filterableFields = $this->getFilterableFields($reflection);

        $joinedTables = [];
        $parameterCount = 0;

        foreach ($filters as $filterName => $value) {
            if (!isset($filterableFields[$filterName])) {
                continue;
            }

            $filterable = $filterableFields[$filterName];
            $paramName = 'param_' . $parameterCount++;

            // Обработка join'ов
            if ($filterable->join && !in_array($filterable->join, $joinedTables)) {
                $this->addJoin($qb, $filterable->join);
                $joinedTables[] = $filterable->join;
            }

            $fieldPath = $filterable->field ?? $this->getFieldPath($filterable, $qb->getRootAliases()[0]);

            $this->applyFilter($qb, $filterable->type, $fieldPath, $value, $paramName);
        }

        return $qb;
    }

    public function getFilterableFields(ReflectionClass $reflection): array
    {
        $fields = [];

        foreach ($reflection->getProperties() as $property) {
            $attributes = $property->getAttributes(Filterable::class);

            if (!empty($attributes)) {
                $filterable = $attributes[0]->newInstance();
                $fields[$filterable->name] = $filterable;
            }
        }

        return $fields;
    }

    private function applyFilter(QueryBuilder $qb, string $type, string $field, mixed $value, string $paramName): void
    {
        match ($type) {
            FilterType::EXACT->value => $qb->andWhere("$field = :$paramName")
                ->setParameter($paramName, $value),

            FilterType::LIKE->value => $qb->andWhere("$field LIKE :$paramName")
                ->setParameter($paramName, '%' . $value . '%'),

            FilterType::IN->value => $qb->andWhere("$field IN (:$paramName)")
                ->setParameter($paramName, is_array($value) ? $value : explode(',', $value)),

            FilterType::BOOLEAN->value => $qb->andWhere("$field = :$paramName")
                ->setParameter($paramName, filter_var($value, FILTER_VALIDATE_BOOLEAN)),

            FilterType::GREATER_THAN->value => $qb->andWhere("$field > :$paramName")
                ->setParameter($paramName, $value),

            FilterType::LESS_THAN->value => $qb->andWhere("$field < :$paramName")
                ->setParameter($paramName, $value),

            FilterType::GREATER_THAN_OR_EQUAL->value => $qb->andWhere("$field >= :$paramName")
                ->setParameter($paramName, $value),

            FilterType::LESS_THAN_OR_EQUAL->value => $qb->andWhere("$field <= :$paramName")
                ->setParameter($paramName, $value),

            FilterType::RANGE->value => $this->applyRangeFilter($qb, $field, $value, $paramName),
            FilterType::DATE_RANGE->value => $this->applyDateRangeFilter($qb, $field, $value, $paramName),

            default => throw new \InvalidArgumentException("Unknown filter type: $type")
        };
    }

    private function applyRangeFilter(QueryBuilder $qb, string $field, mixed $value, string $paramName): void
    {
        if (is_array($value)) {
            if (isset($value['from']) && $value['from'] !== null) {
                $qb->andWhere("$field >= :{$paramName}_from")
                    ->setParameter("{$paramName}_from", $value['from']);
            }
            if (isset($value['to']) && $value['to'] !== null) {
                $qb->andWhere("$field <= :{$paramName}_to")
                    ->setParameter("{$paramName}_to", $value['to']);
            }
        }
    }

    private function applyDateRangeFilter(QueryBuilder $qb, string $field, mixed $value, string $paramName): void
    {
        if (is_array($value)) {
            if (isset($value['from']) && $value['from'] !== null) {
                $qb->andWhere("$field >= :{$paramName}_from")
                    ->setParameter("{$paramName}_from", new \DateTime($value['from']));
            }
            if (isset($value['to']) && $value['to'] !== null) {
                $qb->andWhere("$field <= :{$paramName}_to")
                    ->setParameter("{$paramName}_to", new \DateTime($value['to']));
            }
        }
    }

    private function addJoin(QueryBuilder $qb, string $joinConfig): void
    {
        $parts = explode('.', $joinConfig);
        if (count($parts) === 2) {
            [$relation, $alias] = $parts;
            $rootAlias = $qb->getRootAliases()[0];
            $qb->leftJoin("$rootAlias.$relation", $alias);
        }
    }

    private function getFieldPath(Filterable $filterable, string $rootAlias): string
    {
        if ($filterable->field) {
            return $filterable->field;
        }

        if ($filterable->join) {
            $joinParts = explode('.', $filterable->join);
            if (count($joinParts) === 2) {
                return $joinParts[1] . '.' . $filterable->name;
            }
        }

        return $rootAlias . '.' . $filterable->name;
    }

    private function isValidFilterValue(mixed $value): bool
    {
        if ($value === null || $value === '') {
            return false;
        }

        if (is_array($value) && empty($value)) {
            return false;
        }

        return true;
    }

    private function parseFilterValue(mixed $value): mixed
    {
        // Парсим специальные форматы
        if (is_string($value)) {
            // Обработка диапазонов: "100-500"
            if (preg_match('/^(\d+)-(\d+)$/', $value, $matches)) {
                return ['from' => (int)$matches[1], 'to' => (int)$matches[2]];
            }

            // Обработка списков: "php,javascript,python"
            if (str_contains($value, ',')) {
                return array_map('trim', explode(',', $value));
            }
        }

        return $value;
    }
}