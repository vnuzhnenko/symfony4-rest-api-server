<?php

namespace App\Tests\Functional;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Client;

class ApiAuthenticationTest extends BaseApiControllerTestCase
{
    /**
     * @var Client
     */
    private $client;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    public function testInvalidCredentials()
    {
        $authData = $this->createAuthPair('some_invalid_username', 'some_invalid_password');
        $this->sendRequest($authData);
//        fwrite(STDERR, $this->client->getResponse()->__toString());
        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_UNAUTHORIZED);
    }

    public function testSucessfullLogin()
    {
        $authData = $this->createAuthPair($this->getValidAuthUsername(), $this->getValidAuthPassword());
        $this->sendRequest($authData);

//        fwrite(STDERR, print_r($_ENV, true));
//        fwrite(STDERR, "\n--> Request:\n".$this->client->getRequest()->__toString());
//        fwrite(STDERR, "\n--> Response:\n".$this->client->getResponse()->__toString());
        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_OK);
    }

    protected function sendRequest(array $authData)
    {
        $serverParams = $this->getServerParams();
        $postData = json_encode($authData);
        $this->client->request('POST', '/api/login_check', [], [], $serverParams, $postData);
    }

}