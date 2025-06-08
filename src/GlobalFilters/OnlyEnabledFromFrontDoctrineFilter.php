<?php

namespace App\GlobalFilters;


use App\Helpers\ClassHelper;
use App\Traits\IsEnabledTrait;
use Doctrine\ORM\Query\Filter\SQLFilter;
use Doctrine\ORM\Mapping\ClassMetadata;
use Psr\Log\LoggerInterface;

class OnlyEnabledFromFrontDoctrineFilter extends SQLFilter
{

    private LoggerInterface $logger;

    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }
    /**
     * @param ClassMetadata $targetEntity
     * @param string $targetTableAlias
     * @return string
     */
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias): string
    {
        $entityClass = $targetEntity->getReflectionClass()->getName();

        // Проверяем, использует ли entity нужный trait
        if (!ClassHelper::isCheckUseTrait($entityClass, IsEnabledTrait::class)) {
            return '';
        }

        // Получаем параметр фильтра
        $isEnabledParam = $this->getParameter('is_enabled');

        // Логируем применение фильтра
        if (isset($this->logger)) {
            $this->logger->info('Applying isEnabled filter', [
                'entity' => $entityClass,
                'table_alias' => $targetTableAlias,
                'parameter' => $isEnabledParam
            ]);
        }

        return sprintf('%s.is_enabled = %s', $targetTableAlias, $isEnabledParam);
    }
}