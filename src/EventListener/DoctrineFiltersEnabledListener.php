<?php

namespace App\EventListener;


use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class DoctrineFiltersEnabledListener
{
    /** @var string */
    public const ADMIN_PREFIX = '/admin';

    /** @var string */
    public const SEARCH_IMPORT_COMMAND = 'app:search-import';

    /** @var string */
    public const FILTER_CHECK_FUNCTION_PREFIX = 'checkAndEnabledFilter';

    /** @var string */
    public const FILTER_SET_FUNCTION_PREFIX = 'setEnabledFilter';

    /**
     * Белый лист роутов для деактивации фильтра isModerated=true
     */
    public const WHITE_LIST_IS_MODERATED_ROUTE_NAME = [
        'get_user_personal_adverts',
        'get_user_personal_company',
        'api_edit_personal_advert',
        'api_resume_user_personal',
        'api_resume_edit_personal',
        'api_resume_single_personal',
        'get_user_personal_single_advert'
    ];

    /**
     * Белый лист роутов для деактивации фильтра isEnabled=true
     */
    public const WHITE_LIST_IS_ENABLED_ROUTE_NAME = [
        'get_user_personal_adverts',
        'get_user_personal_vacancies',
        'get_user_personal_company',
        'api_edit_personal_company',
        'get_user_personal_single_company',
        'get_user_personal_single_advert',
        'get_single_personal_vacancy',
        'api_edit_personal_advert',
        'api_edit_personal_vacancy'
    ];

    /**
     * DoctrineFiltersEnabledListener constructor.
     * @param EntityManager $em
     */
    public function __construct(
        private readonly EntityManagerInterface $em
    )
    {
    }


    /**
     * @param RequestEvent $event
     * @return void
     */
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
     * @param ConsoleCommandEvent $event
     * @return void
     */
    public function onConsoleCommand(ConsoleCommandEvent $event): void
    {
        if ($event->getCommand()->getName() === self::SEARCH_IMPORT_COMMAND){
            $methodList = get_class_methods($this);

            foreach ($methodList as $method) {
                if (str_contains($method, self::FILTER_SET_FUNCTION_PREFIX)) {
                    $this->$method();
                }
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
        $filter = $this->em
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
        $filter = $this->em
            ->getFilters()
            ->enable('only_moderated_from_front_doctrine_filter');

        $filter->setParameter('is_moderated', true);
    }
}