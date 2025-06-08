<?php

namespace App\Tests\Functional\Api;


use App\Entity\User;

class UserApiTest extends AbstractApiCase
{


    public function testRegistration()
    {
        $data = [
            'firstName' => 'testFirstName',
            'lastName' => 'testLastName',
            'password' => 'testPassword',
            'email' => 'testa@test.com',
            'phone' => '89192166761'
        ];

        $response = $this->apiRequest(
                     $this->router->generate('api_registration'),
                     'POST',
                     content: json_encode($data),
        );

        $this->assertEquals(201, $response->getStatusCode());
        $userData = $this->em->getRepository(User::class)->findOneBy(['email' => 'testa@test.com']);
        $this->assertEquals($data['firstName'], $userData->getFirstName());
        $this->assertEquals($data['lastName'], $userData->getLastName());
        $this->assertEquals($data['email'], $userData->getEmail());
    }

    public function testRegistrationWrong()
    {
        $data = [
            'firstName' => '',
            'lastName' => '',
            'password' => '',
            'email' => '',
            'phone' => ''
        ];

        $response = $this->apiRequest(
            $this->router->generate('api_registration'),
            'POST',
            content: json_encode($data),
        );

        $this->assertEquals(400, $response->getStatusCode());
        $content = json_decode($response->getContent(), true);
        $this->assertEquals('Validation Failed', $content['form']['message']);
    }
}