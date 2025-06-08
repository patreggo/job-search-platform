<?php

namespace App\EventSubscriber;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\ConsoleEvents;

class DoctrineFiltersEnabledSubscriber implements EventSubscriberInterface
{
    /** @var string */
    public const ADMIN_PREFIX = '/admin';

    /** @var string */
    public const SEARCH_IMPORT_COMMAND = 'app:search-import';

    /** @var string */
    public const FILTER_CHECK_FUNCTION_PREFIX = 'checkAndEnabledFilter';

    /** @var string */
    public const FILTER_SET_FUNCTION_PREFIX = 'setEnabledFilter';

    public const WHITE_LIST_IS_MODERATED_ROUTE_NAME = [
        'api_resume_personal',
        'api_resume_new',
        'api_resume_single',
        'api_resume_list',
        'api_company_personal',
        // Добавляем API маршруты вакансий
        'api_get_many_vacancies',
        'api_get_single_vacancy',
        'api_create_new_vacancy',
        'api_edit_vacancy',
        'api_patch_vacancy',
        'api_delete_vacancy',
        'api_personal',
    ];

    /**
     * Белый лист роутов для деактивации фильтра isEnabled=true
     */
    public const WHITE_LIST_IS_ENABLED_ROUTE_NAME = [
        'api_resume_personal',
        'api_resume_new',
        'api_resume_single',
        'api_resume_list',
        'api_company_personal',
        // Добавляем API маршруты вакансий
        'api_get_many_vacancies',
        'api_get_single_vacancy',
        'api_create_new_vacancy',
        'api_edit_vacancy',
        'api_patch_vacancy',
        'api_delete_vacancy',
        'api_personal',
    ];

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $uri = $event->getRequest()->getRequestUri();
        $routeName = $event->getRequest()->attributes->get('_route');
        $methodList = get_class_methods($this);

        foreach ($methodList as $method) {
            if (str_contains($method, self::FILTER_CHECK_FUNCTION_PREFIX)) {
                $this->$method(
                    $uri,
                    $routeName
                );
            }
        }
    }

    /**
     * @param string $uri
     * @param string|null $routeNamed
     * @return void
     */
    public function checkAndEnabledFilterIsEnabled(string $uri, ?string $routeNamed): void
    {
        if (
            str_contains($uri, self::ADMIN_PREFIX) ||
            in_array(
                $routeNamed,
                self::WHITE_LIST_IS_ENABLED_ROUTE_NAME,
                true
            )
        ) {
            return;
        }
        $this->setEnabledFilterIsEnabled();
    }

    /**
     * @param string $uri
     * @param string|null $routeNamed
     * @return void
     */
    public function checkAndEnabledFilterIsModerated(string $uri, ?string $routeNamed): void
    {
        if (
            str_contains($uri, self::ADMIN_PREFIX) ||
            in_array(
                $routeNamed,
                self::WHITE_LIST_IS_MODERATED_ROUTE_NAME,
                true
            )
        ) {
            return;
        }
        $this->setEnabledFilterIsModerated();
    }

    /**
     * @return void
     */
    private function setEnabledFilterIsEnabled(): void
    {
        $filter = $this->entityManager
            ->getFilters()
            ->enable('only_enabled_from_front_doctrine_filter');

        $filter->setParameter('is_enabled', true);
    }

    /**
     * @return void
     */
    private function setEnabledFilterIsModerated(): void
    {
        error_log('Enabling is_enabled filter');
        $filter = $this->entityManager
            ->getFilters()
            ->enable('only_moderated_from_front_doctrine_filter');

        $filter->setParameter('is_moderated', true);
    }
}