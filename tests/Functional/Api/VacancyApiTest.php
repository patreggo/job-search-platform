<?php

namespace App\Tests\Functional\Api;


use App\Entity\User;
use App\Entity\Vacancy\Vacancy;

class VacancyApiTest extends AbstractApiCase
{


    public function testApiNewVacancy()
    {
        $this->createUser();
        $this->em->flush();

        $data = [
            'name' => 'test',
            'workAddress' => 'test',
            'description' => 'test',
            'specializations' => [
                1
            ],
            'isEnabled' => true,
        ];

        $response = $this->apiRequest(
            $this->router->generate('api_create_new_vacancy'),
            'POST',
            headers: [
                'HTTP_AUTHORIZATION' => sprintf(
                    'Bearer %s',
                    $this->getAuthToken(self::EMAIL, self::PASSWORD)
                ),
            ],
            content: json_encode($data),

        );
        $content = json_decode($response->getContent(), true);

        $this->assertEquals(201, $response->getStatusCode());

        $vacancyData = $this->em->getRepository(Vacancy::class)->findOneBy(['id' => $content['id']]);
        $this->assertEquals($data['name'], $vacancyData->getName());
        $this->assertEquals($data['workAddress'], $vacancyData->getWorkAddress());
    }

    public function testApiNewVacancyWrong()
    {
        $data = [
            'name' => '',
            'workAddress' => '',
            'description' => '',
            'specializations' => [
                1
            ],
        ];

        $response = $this->apiRequest(
            $this->router->generate('api_create_new_vacancy'),
            'POST',
            content: json_encode($data),
        );

        $this->assertEquals(400, $response->getStatusCode());
        $content = json_decode($response->getContent(), true);
        $this->assertEquals('Validation Failed', $content['form']['message']);
    }
}