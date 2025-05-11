<?php

namespace App\DataFixtures;

use App\Entity\City;
use App\Entity\Country;
use App\Entity\District;
use Doctrine\Persistence\ObjectManager;
use Exception;
use JsonException;

class CityFixtures extends AbstractFixtures
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
     * @throws JsonException
     */
    protected function loadDataCountry(ObjectManager $manager): void
    {
        $fixturesFileDir = __DIR__ . '/JsonData/City';

        foreach ($this->getFixturesData($fixturesFileDir, 'country.json') as $country) {
            $countryEntity = new Country();
            $countryEntity->setName($country['name']);
            $countryEntity->setTechName($country['techName']);
            $countryEntity->setPosition($country['position']);

            $manager->persist($countryEntity);
        }

        $manager->flush();
    }


    /**
     * @param ObjectManager $manager
     * @throws JsonException
     */
    protected function loadDataDistrict(ObjectManager $manager): void
    {
        $fixturesFileDir = __DIR__ . '/JsonData/City';

        foreach ($this->getFixturesData($fixturesFileDir, 'district.json') as $district) {
            $districtEntity = new District();
            $districtEntity->setName($district['name']);
            $districtEntity->setTechName($district['tech_name']);
            $districtEntity->setNationalName($district['national_name']);
            $districtEntity->setImportPlaceType(json_decode($district['import_place_type'], true));
            $districtEntity->setCountry(
                $manager->getRepository(Country::class)->findOneBy(['techName' => $district['country']])
            );

            $manager->persist($districtEntity);
        }
        $manager->flush();
    }

    /**
     * @param ObjectManager $manager
     * @throws JsonException
     */
    protected function loadDataCity(ObjectManager $manager): void
    {
        $district = [];
        $fixturesFileDir = __DIR__ . '/JsonData/City';

        foreach ($this->getFixturesData($fixturesFileDir, 'city.json') as $city) {
            $cityEntity = new City();
            $cityEntity->setName($city['name']);
            $cityEntity->setTechName($city['techName']);
            $cityEntity->setNationalName($city['nationalName']);

            if(!isset($district[$city['district']])){
                $district[$city['district']] = $manager
                    ->getRepository(District::class)
                    ->findOneBy(['techName' => $city['district']]);
            }

            $cityEntity->setDistrict($district[$city['district']]);

            $manager->persist($cityEntity);
        }
        $manager->flush();
    }
}
