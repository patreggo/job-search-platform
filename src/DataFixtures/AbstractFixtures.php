<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\Id\AssignedGenerator;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\Mapping\ClassMetadata;

abstract class AbstractFixtures extends Fixture
{
    /** @var string */
    private const FIXTURE_METHOD_PREFIX = 'loadData';

    /**
     * @param string $fixturesFileDir
     * @param $fixturesFile
     * @return array
     * @throws \JsonException
     */
    protected function getFixturesData(string $fixturesFileDir, $fixturesFile): array
    {
        return json_decode(
            file_get_contents($fixturesFileDir . '/' . $fixturesFile),
            true,
            512,
            JSON_THROW_ON_ERROR
        );
    }

    /**
     * Устанавливаем возможность ручной установки ID у сущности на время загрузки фикстур
     * @param ObjectManager $manager
     * @param string $entityClass
     * @return void
     */
    protected function enableManualSetId(ObjectManager $manager, string $entityClass): void
    {
        $metadata = $manager->getClassMetaData($entityClass);
        $metadata->setIdGenerator(new AssignedGenerator());
        $metadata->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_NONE);
    }

    /**
     * @param ObjectManager $manager
     * @param object $fixturesClass
     * @return void
     * @throws \Exception
     */
    protected function exec(ObjectManager $manager, object $fixturesClass): void
    {
        $methodList = get_class_methods($fixturesClass);
        $methodCount = 0;
        foreach ($methodList as $method) {
            if (str_contains($method, self::FIXTURE_METHOD_PREFIX)) {
                $fixturesClass->$method($manager);
                $methodCount++;
            }
        }

        if ($methodCount === 0) {
            throw new \Exception(
                'empty fixtures class: ' . $fixturesClass::class . '. FIXTURE FUNCTIONS MUST BE PROTECTED OR PUBLIC'
            );
        }
    }

    /**
     * @param string $fixturesFileDir
     * @return array
     */
    protected function findFixturesInDir(string $fixturesFileDir): array
    {
        $fixturesFile = [];
        $fileListInDir = scandir($fixturesFileDir);

        foreach ($fileListInDir as $fileName) {
            if (str_contains($fileName, '.json')) {
                $fixturesFile[] = $fileName;
            }
        }

        return $fixturesFile;
    }
}