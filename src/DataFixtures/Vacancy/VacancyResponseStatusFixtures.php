<?php

namespace App\DataFixtures\Vacancy;

use App\DataFixtures\AbstractFixtures;
use App\Entity\Resume\VacancyResponseStatus;
use Doctrine\Persistence\ObjectManager;
use Exception;
use JsonException;

class VacancyResponseStatusFixtures extends AbstractFixtures
{
    /**
     * @param ObjectManager $manager
     * @return void
     * @throws Exception
     */
    public function load(ObjectManager $manager): void
    {
        $this->exec($manager, $this);
    }

    /**
     * @param ObjectManager $manager
     * @return void
     * @throws JsonException
     */
    protected function loadDataVacancyResponseStatus(ObjectManager $manager): void
    {
        foreach ($this->getFixturesData(__DIR__, 'vacancy_response_status.json') as $vacancyResponseStatus) {
            $vacancyResponseStatusEntity = new VacancyResponseStatus();
            $vacancyResponseStatusEntity->setName($vacancyResponseStatus['name_translator_key']);
            $vacancyResponseStatusEntity->setTechName($vacancyResponseStatus['techName']);

            $manager->persist($vacancyResponseStatusEntity);
        }
        $manager->flush();
    }
}