<?php

namespace App\Tests\Functional;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


abstract class BaseApiControllerTestCase extends WebTestCase
{
    /**
     * @var string
     */
    const AUTH_PREFIX = 'Bearer';

    /**
     * @var string
     */
    const QUERY_PARAMETER_NAME = 'bearer';

    protected function getServerParams(): array
    {
        return [
            'CONTENT_TYPE' => 'application/json',
        ];
    }

    /**
     * Create a client with a default Authorization header.
     *
     * @param string $username
     * @param string $password
     *
     * @return \Symfony\Bundle\FrameworkBundle\Client
     */
    protected function createAuthenticatedClient($username = 'user', $password = 'password')
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/api/login_check',
            [],
            [],
            $this->getServerParams(),
            json_encode($this->createAuthPair($username, $password))
        );
        $response = $client->getResponse();
        $data = json_decode($response->getContent(), true);
//        fwrite(STDERR, "\n--> Request:\n".$client->getRequest()->__toString());
//        fwrite(STDERR, "\n--> Response:\n".$client->getResponse()->__toString());
//        fwrite(STDERR, print_r($data, true));
        $this->assertArrayHasKey('token', $data, $response->getContent());
        return static::createClient(
            [],
            ['HTTP_Authorization' => sprintf('%s %s', self::AUTH_PREFIX, $data['token'])]
        );
    }

    /**
     * @param Response $response
     * @param int      $statusCode
     * @param bool     $checkValidJson
     */
    protected function assertJsonResponse(Response $response, $statusCode = 200, $checkValidJson = true)
    {
        $this->assertEquals($statusCode, $response->getStatusCode(), $response->getContent());
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'), $response->headers);
        if ($checkValidJson) {
            $decode = json_decode($response->getContent(), true);
            $this->assertTrue(
                ($decode !== null && $decode !== false),
                'is response valid json: [' . $response->getContent() . ']'
            );
        }
    }

    protected function sendRequest(array $authData)
    {
        $serverParams = $this->getServerParams();
        $postData = json_encode($authData);
        $this->client->request('POST', '/api/login_check', [], [], $serverParams, $postData);
    }

    /**
     * @param string $username
     * @param string $password
     * @return array
     */
    protected function createAuthPair(string $username, string $password): array
    {
        return [
            'username' => $username,
            'password' => $password,
        ];
    }

    protected function getValidAuthUsername(): string
    {
        return getenv('AUTH_DEFAULT_USERNAME');
    }

    protected function getValidAuthPassword(): string
    {
        return getenv('AUTH_DEFAULT_PASSWORD');
    }
}