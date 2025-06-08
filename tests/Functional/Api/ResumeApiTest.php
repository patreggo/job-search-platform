<?php

namespace App\Tests\Functional\Api;


use App\Entity\Resume\Resume;
use App\Entity\User;
use App\Entity\Vacancy\Vacancy;

class ResumeApiTest extends AbstractApiCase
{


    public function testApiNewResume()
    {
        $this->createUser();
        $this->em->flush();

        $data = [
            'specialization' => 1,
            'firstName' => 'test',
            'lastName' => 'test',
            'workSchedule' => 1,
            'employmentType' => [1],
            'residenceCity' => 1,
            'citizenship' => [1]
        ];

        $response = $this->apiRequest(
            $this->router->generate('api_resume_new'),
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
        $resumeData = $this->em->getRepository(Resume::class)->findOneBy(['id' => $content['id']]);
        $this->assertEquals($data['firstName'], $resumeData->getFirstName());
        $this->assertEquals($data['lastName'], $resumeData->getLastName());
    }

    public function testApiNewResumeWrong()
    {
        $this->createUser();
        $this->em->flush();

        $data = [
            'specialization' => '',
            'firstName' => 'test',
            'lastName' => 'test',
            'workSchedule' => 1,
            'employmentType' => [],
            'residenceCity' => 1,
            'citizenship' => []
        ];

        $response = $this->apiRequest(
            $this->router->generate('api_resume_new'),
            'POST',
            headers: [
                'HTTP_AUTHORIZATION' => sprintf(
                    'Bearer %s',
                    $this->getAuthToken(self::EMAIL, self::PASSWORD)
                ),
            ],
            content: json_encode($data),

        );
        $this->assertEquals(400, $response->getStatusCode());
        $content = json_decode($response->getContent(), true);
        $this->assertEquals('Validation Failed', $content['form']['message']);
    }
}