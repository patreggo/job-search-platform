<?php

namespace App\GlobalFilters;

use App\Helpers\ClassHelper;
use App\Traits\IsModeratedTrait;
use Doctrine\ORM\Query\Filter\SQLFilter;
use Doctrine\ORM\Mapping\ClassMetadata;
use Psr\Log\LoggerInterface;

class OnlyModeratedFromFrontDoctrineFilter extends SQLFilter
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
        if (!ClassHelper::isCheckUseTrait($entityClass, IsModeratedTrait::class)) {
            return '';
        }

        // Получаем параметр фильтра
        $isModeratedParam = $this->getParameter('is_moderated');

        // Логируем применение фильтра
        if (isset($this->logger)) {
            $this->logger->info('Applying isModerated filter', [
                'entity' => $entityClass,
                'table_alias' => $targetTableAlias,
                'parameter' => $isModeratedParam
            ]);
        }

        return sprintf('%s.is_moderated = %s', $targetTableAlias, $isModeratedParam);
    }
}