<?php

namespace App\DataFixtures\User;

use App\DataFixtures\AbstractFixtures;
use App\Entity\User;
use App\Entity\UserRoles;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ObjectManager;
use JsonException;

/**
 * Class UserFixtures
 * @package App\DataFixtures
 */
class UserFixtures extends AbstractFixtures
{

    /**
     * @param ObjectManager $manager
     * @throws \Exception
     */
    public function load(ObjectManager $manager): void
    {
        $this->exec($manager, $this);
    }

    /**
     * Load user Roles
     * @throws JsonException
     */
    protected function loadDataRoles(ObjectManager $manager): void
    {
        $fixturesFileDir = __DIR__ . '/Fixtures/Role';

        $fixturesFileList = $this->findFixturesInDir($fixturesFileDir);
        foreach ($fixturesFileList as $fixturesFile) {
            foreach ($this->getFixturesData($fixturesFileDir, $fixturesFile) as $role) {
                $roleEntity = new UserRoles();
                $roleEntity->setDisplayName($role['displayName']);
                $roleEntity->setName($role['name']);
                $manager->persist($roleEntity);
            }
        }
        $manager->flush();
    }


    /**
     * @param ObjectManager $manager
     * @throws JsonException
     * @throws NonUniqueResultException
     */
    protected function loadDataUser(ObjectManager $manager): void
    {
        $fixturesFileDir = __DIR__ . '/Fixtures/User';

        $fixturesFileList = $this->findFixturesInDir($fixturesFileDir);
        foreach ($fixturesFileList as $fixturesFile) {
            foreach ($this->getFixturesData($fixturesFileDir, $fixturesFile) as $user) {
                $userEntity = new User();
                $userEntity->setEmail($user['email']);
                $userEntity->setRoles(
                    $manager
                        ->getRepository(UserRoles::class)
                        ->getRoleByName($user['role_name'])
                );
                $userEntity->setPassword($user['password']);
                $manager->persist($userEntity);
            }
        }
        $manager->flush();
    }
}