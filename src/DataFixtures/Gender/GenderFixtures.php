<?php

namespace App\DataFixtures\Gender;

use App\DataFixtures\AbstractFixtures;
use App\Entity\Resume\Gender;
use Doctrine\Persistence\ObjectManager;
use Exception;
use JsonException;

class GenderFixtures extends AbstractFixtures
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
    protected function loadDataGender(ObjectManager $manager): void
    {
        foreach ($this->getFixturesData(__DIR__, 'gender.json') as $gender) {
            $genderEntity = new Gender();
            $genderEntity->setName($gender['name']);
            $genderEntity->setTechName($gender['techName']);

            $manager->persist($genderEntity);
        }
        $manager->flush();
    }
}