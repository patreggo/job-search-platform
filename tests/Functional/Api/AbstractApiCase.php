<?php


namespace App\Tests\Functional\Api;

use App\Entity\City;
use App\Entity\Company;
use App\Entity\Country;
use App\Entity\District;
use App\Entity\User;
use App\Entity\UserRoles;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\NotSupported;
use Doctrine\ORM\Exception\ORMException;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

abstract class AbstractApiCase extends WebTestCase
{
    protected const
        EMAIL = "mail@mail23.ru",
        ROLE_USER = UserRoles::ROLE_USER,
        PASSWORD = "Fg3i@saur43",
        ROLE_USER_AUTHOR = UserRoles::ROLE_USER_AUTHOR;


    /** @var KernelBrowser|null */
    protected KernelBrowser|null $client = null;

    /** @var RouterInterface|null */
    protected RouterInterface|null $router = null;

    protected EntityManager|null $em = null;

    protected Connection|null $connection = null;

    protected string $projectDir;


    public function setUp(): void
    {
        parent::setUp();
        $this->client = self::createClient();
        $this->client->disableReboot();
        $this->connection = static::getContainer()->get('doctrine')->getConnection();
        $this->connection->beginTransaction();
        $this->iniDi();
    }

    public function iniDi(): void
    {
        $this->router = static::getContainer()->get(RouterInterface::class);
        $this->em = static::getContainer()->get('doctrine.orm.default_entity_manager');
        $this->projectDir = static::getContainer()->get('kernel')->getProjectDir();
    }

    public function tearDown(): void
    {
        parent::teardown();
        unset($this->em);
        if ($this->connection && $this->connection->isTransactionActive()) {
            $this->connection->rollBack();
        }
    }

    public function createCompany(
        ?User  $user = null,
        bool   $isConfirmed = true,
        string $name = 'testCompany',
        string $companyEmail = 'testCompany@test.com',
        string $companyPhone = '+79999999832',
        string $testDescription = 'testDescription',
        bool $isEnabled = true,
    ): Company
    {
        $company = new Company();
        $company->setUser($user ?? $this->createUser('company@owner.ru'));
        $company->setIsConfirmed($isConfirmed);
        $company->setName($name);
        $company->setEmail($companyEmail);
        $company->setContactPhone($companyPhone);
        $company->setDescription($testDescription);
        $company->setIsEnabled($isEnabled);

        $this->em->persist($company);

        return $company;
    }

    /**
     * @param string $url
     * @param string $method
     * @param array $parameters
     * @param array $files
     * @param array $headers
     * @param string|null $content
     * @return Response
     */
    public function apiRequest(
        string $url,
        string $method = Request::METHOD_GET,
        array  $parameters = [],
        array  $files = [],
        array  $headers = [],
        string $content = null
    ): Response
    {
        $this->client->request($method, $url, $parameters, $files, $headers, $content);

        return $this->client->getResponse();
    }

    /**
     * @param string $email
     * @return User|null
     * @throws NotSupported
     */
    protected function getUser(string $email): ?User
    {
        return $this->em->getRepository(User::class)->findOneBy([
            "email" => $email,
        ]);
    }

    /**
     * @param string $email
     * @param string $password
     * @param string $role
     * @return User
     * @throws NotSupported
     * @throws ORMException
     */
    protected function createUser(
        string $email = self::EMAIL,
        string $password = self::PASSWORD,
        string $role = self::ROLE_USER,
    ): User
    {
        $user = new User();
        $user->setEmail($email);
        $user->setRoles($this->getUserRole($role));
        $hashedPassword = $this->getContainer()
            ->get('security.user_password_hasher')
            ->hashPassword($user, $password);
        $user->setPassword($hashedPassword);
        $this->em->persist($user);

        return $user;
    }

    /**
     * @param string $role
     * @return UserRoles
     * @throws NotSupported
     */
    protected function getUserRole(string $role = UserRoles::ROLE_USER): UserRoles
    {
        $roles = $this->em->getRepository(UserRoles::class)->getRoleByName($role);

        $this->assertNotNull($roles);

        return $roles;
    }


    protected function getAuthToken(string $email, string $password, int $responseStatusCode = 200): string
    {
        $url = $this->router->generate('api_login_check');

        $response = $this->apiRequest(
            $url,
            Request::METHOD_POST,
            headers: [
                'CONTENT_TYPE' => 'application/json',
            ],
            content: json_encode(
                [
                    "email" => $email,
                    "password" => $password,
                ]
            )
        );
        $this->assertEquals($responseStatusCode, $response->getStatusCode());

        if (Response::HTTP_OK === $responseStatusCode) {
            $content = \json_decode($response->getContent(), true);

            $this->assertArrayHasKey('token', $content);

            return $content['token'];
        } else {
            return 'wrong';
        }
    }


    /**
     * @param string|null $cityName
     * @param string|null $techName
     * @param District|null $district
     * @return City
     * @throws ORMException
     */
    protected function createCity(
        ?string   $cityName = null,
        ?string   $techName = null,
        ?District $district = null
    ): City
    {
        $city = new City();
        $city->setName($cityName ?? 'testName');
        $city->setTechName($techName ?? 'testTechName');
        $city->setDistrict($district ?? $this->createDistrict());

        $this->em->persist($city);

        return $city;
    }

    /**
     * @param string $name
     * @param string $techName
     * @param Country|null $country
     * @param City|null $city
     * @return District
     * @throws ORMException
     */
    protected function createDistrict(
        string   $name = 'testCity',
        string   $techName = 'testTechName',
        ?Country $country = null,
        ?City    $city = null
    ): District
    {
        $district = new District();
        $district->setName($name);
        $district->setCountry($country ?? $this->createCountry());
        $district->setTechName($techName);

        $this->em->persist($district);

        return $district;
    }

    /**
     * @param string $name
     * @param string $techName
     * @return Country
     * @throws ORMException
     */
    protected function createCountry(string $name = 'testCountry', string $techName = 'testTechName'): Country
    {
        $country = new Country();

        $country->setName($name);
        $country->setTechName($techName);
        $country->setPosition(228);

        $this->em->persist($country);

        return $country;
    }


    /**
     * @param User $user
     * @return void
     */
    protected function prepareSecurityMock(User $user): void
    {
        $securityMock = $this
            ->getMockBuilder(Security::class)
            ->disableOriginalConstructor()
            ->getMock();

        $securityMock->method('getUser')->willReturn($user);

        static::getContainer()->set(Security::class, $securityMock);
    }

}
