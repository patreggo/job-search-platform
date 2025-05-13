<?php

namespace App\DataFixtures\Vacancy;

use App\DataFixtures\AbstractFixtures;
use App\Entity\Vacancy\AbstractVacancyParameters;
use App\Entity\Vacancy\VacancyCommunicationType;
use App\Entity\Vacancy\VacancyCompanyIndustry;
use App\Entity\Vacancy\VacancyEducation;
use App\Entity\Vacancy\VacancyEmploymentType;
use App\Entity\Vacancy\VacancyIncomePayment;
use App\Entity\Vacancy\VacancyIncomePaymentPeriod;
use App\Entity\Vacancy\VacancyInteractionLanguages;
use App\Entity\Vacancy\VacancyKeySkills;
use App\Entity\Vacancy\VacancyRegistrationMethods;
use App\Entity\Vacancy\VacancyRelocation;
use App\Entity\Vacancy\VacancySpecializations;
use App\Entity\Vacancy\VacancyWorkExperience;
use App\Entity\Vacancy\VacancyWorkSchedule;
use Doctrine\Persistence\ObjectManager;
use Exception;
use JsonException;

class VacancyParameterFixtures extends AbstractFixtures
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
     * @param AbstractVacancyParameters $vacancyParameterEntity
     * @param array $vacancyParameter
     * @param int $position
     * @return void
     */
    private function setData(
        AbstractVacancyParameters $vacancyParameterEntity,
        array $vacancyParameter,
        int $position
    ): void {
        $vacancyParameterEntity->setName($vacancyParameter['name']);
        $vacancyParameterEntity->setTechName($vacancyParameter['techName']);
        $vacancyParameterEntity->setPosition($position);
    }

    /**
     * @param ObjectManager $manager
     * @return void
     * @throws JsonException
     */
    protected function loadDataCommunicationType(ObjectManager $manager): void
    {
        $i = 1;
        foreach ($this->getFixturesData(__DIR__, 'communication_type.json') as $vacancyParameter) {
            $vacancyParameterEntity = new VacancyCommunicationType();
            $this->setData($vacancyParameterEntity, $vacancyParameter, $i);
            $manager->persist($vacancyParameterEntity);
            $i++;
        }
        $manager->flush();
    }

    /**
     * @param ObjectManager $manager
     * @return void
     * @throws JsonException
     */
    protected function loadDataCompanyIndustry(ObjectManager $manager): void
    {
        $i = 1;
        foreach ($this->getFixturesData(__DIR__, 'company_industry.json') as $vacancyParameter) {
            $vacancyParameterEntity = new VacancyCompanyIndustry();
            $this->setData($vacancyParameterEntity, $vacancyParameter, $i);

            $manager->persist($vacancyParameterEntity);
            $i++;
        }
        $manager->flush();
    }
    /**
     * @param ObjectManager $manager
     * @return void
     * @throws JsonException
     */
    protected function loadDataEducation(ObjectManager $manager): void
    {
        $i = 1;
        foreach ($this->getFixturesData(__DIR__, 'education.json') as $vacancyParameter) {
            $vacancyParameterEntity = new VacancyEducation();
            $this->setData($vacancyParameterEntity, $vacancyParameter, $i);

            $manager->persist($vacancyParameterEntity);
            $i++;
        }
        $manager->flush();
    }
    /**
     * @param ObjectManager $manager
     * @return void
     * @throws JsonException
     */
    protected function loadDataEmploymentType(ObjectManager $manager): void
    {
        $i = 1;
        foreach ($this->getFixturesData(__DIR__, 'employment_type.json') as $vacancyParameter) {
            $vacancyParameterEntity = new VacancyEmploymentType();
            $this->setData($vacancyParameterEntity, $vacancyParameter, $i);

            $manager->persist($vacancyParameterEntity);
            $i++;
        }
        $manager->flush();
    }
    /**
     * @param ObjectManager $manager
     * @return void
     * @throws JsonException
     */
    protected function loadDataIncomePayment(ObjectManager $manager): void
    {
        $i = 1;
        foreach ($this->getFixturesData(__DIR__, 'income_payment.json') as $vacancyParameter) {
            $vacancyParameterEntity = new VacancyIncomePayment();
            $this->setData($vacancyParameterEntity, $vacancyParameter, $i);

            $manager->persist($vacancyParameterEntity);
            $i++;
        }
        $manager->flush();
    }
    /**
     * @param ObjectManager $manager
     * @return void
     * @throws JsonException
     */
    protected function loadDataIncomePaymentPeriod(ObjectManager $manager): void
    {
        $i = 1;
        foreach ($this->getFixturesData(__DIR__, 'income_payment_period.json') as $vacancyParameter) {
            $vacancyParameterEntity = new VacancyIncomePaymentPeriod();
            $this->setData($vacancyParameterEntity, $vacancyParameter, $i);

            $manager->persist($vacancyParameterEntity);
            $i++;
        }
        $manager->flush();
    }
    /**
     * @param ObjectManager $manager
     * @return void
     * @throws JsonException
     */
    protected function loadDataInteractionLanguages(ObjectManager $manager): void
    {
        $i = 1;
        foreach ($this->getFixturesData(__DIR__, 'interaction_languages.json') as $vacancyParameter) {
            $vacancyParameterEntity = new VacancyInteractionLanguages();
            $this->setData($vacancyParameterEntity, $vacancyParameter, $i);

            $manager->persist($vacancyParameterEntity);
            $i++;
        }
        $manager->flush();
    }
    /**
     * @param ObjectManager $manager
     * @return void
     * @throws JsonException
     */
    protected function loadDataKeySkills(ObjectManager $manager): void
    {
        $i = 1;
        foreach ($this->getFixturesData(__DIR__, 'key_skills.json') as $vacancyParameter) {
            $vacancyParameterEntity = new VacancyKeySkills();
            $this->setData($vacancyParameterEntity, $vacancyParameter, $i);

            $manager->persist($vacancyParameterEntity);
            $i++;
        }
        $manager->flush();
    }
    /**
     * @param ObjectManager $manager
     * @return void
     * @throws JsonException
     */
    protected function loadDataRelocation(ObjectManager $manager): void
    {
        $i = 1;
        foreach ($this->getFixturesData(__DIR__, 'relocation.json') as $vacancyParameter) {
            $vacancyParameterEntity = new VacancyRelocation();
            $this->setData($vacancyParameterEntity, $vacancyParameter, $i);

            $manager->persist($vacancyParameterEntity);
            $i++;
        }
        $manager->flush();
    }
    /**
     * @param ObjectManager $manager
     * @return void
     * @throws JsonException
     */
    protected function loadDataSpecializations(ObjectManager $manager): void
    {
        $i = 1;
        foreach ($this->getFixturesData(__DIR__, 'specializations.json') as $vacancyParameter) {
            $vacancyParameterEntity = new VacancySpecializations();
            $this->setData($vacancyParameterEntity, $vacancyParameter, $i);

            $manager->persist($vacancyParameterEntity);
            $i++;
        }
        $manager->flush();
    }
    /**
     * @param ObjectManager $manager
     * @return void
     * @throws JsonException
     */
    protected function loadDataRegistrationMethods(ObjectManager $manager): void
    {
        $i = 1;
        foreach ($this->getFixturesData(__DIR__, 'registration_methods.json') as $vacancyParameter) {
            $vacancyParameterEntity = new VacancyRegistrationMethods();
            $this->setData($vacancyParameterEntity, $vacancyParameter, $i);

            $manager->persist($vacancyParameterEntity);
            $i++;
        }
        $manager->flush();
    }
    /**
     * @param ObjectManager $manager
     * @return void
     * @throws JsonException
     */
    protected function loadDataWorkExperience(ObjectManager $manager): void
    {
        $i = 1;
        foreach ($this->getFixturesData(__DIR__, 'work_experience.json') as $vacancyParameter) {
            $vacancyParameterEntity = new VacancyWorkExperience();
            $this->setData($vacancyParameterEntity, $vacancyParameter, $i);

            $manager->persist($vacancyParameterEntity);
            $i++;
        }
        $manager->flush();
    }

    /**
     * @param ObjectManager $manager
     * @return void
     * @throws JsonException
     */
    protected function loadDataWorkSchedule(ObjectManager $manager): void
    {
        $i = 1;
        foreach ($this->getFixturesData(__DIR__, 'work_schedule.json') as $vacancyParameter) {
            $vacancyParameterEntity = new VacancyWorkSchedule();
            $this->setData($vacancyParameterEntity, $vacancyParameter, $i);

            $manager->persist($vacancyParameterEntity);
            $i++;
        }
        $manager->flush();
    }
}