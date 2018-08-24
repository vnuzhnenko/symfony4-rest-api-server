<?php

namespace App\Tests\Functional;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Client;

class TasksApiFunctionalTest extends BaseApiControllerTestCase
{
    /**
     * @var Client
     */
    private $client;

    public function setUp()
    {
        $this->client = static::createAuthenticatedClient($this->getValidAuthUsername(), $this->getValidAuthPassword());
    }

    public function testTasksIsAccessible()
    {
        $this->client->request('GET', '/api/tasks',[], [], $this->getServerParams());
        $response = $this->client->getResponse();
        $this->assertTrue($response->isSuccessful(), $response->getContent());
    }

    /**
     * @dataProvider validTaskDataProvider
     */
    public function testAddNewValidTask($content, $completed)
    {
        $response = $this->createNewTask($content, $completed);
        $this->assertJsonResponse($response, Response::HTTP_CREATED);
    }

    /**
     * @dataProvider invalidTaskDataProvider
     */
    public function testAddNewInvalidTask($content, $completed)
    {
        $response = $this->createNewTask($content, $completed);
        //$this->assertJsonResponse($response, Response::HTTP_OK);
        $this->assertFalse($response->isSuccessful(), $response->getContent());
    }

    /**
     * @param $content
     * @param $completed
     * @return mixed
     */
    protected function createNewTask($content, $completed)
    {
        $payload = [
            'content' => $content,
            'completed' => $completed
        ];
        $this->client->request('POST', '/api/tasks',[], [], $this->getServerParams(), json_encode($payload));
        return $this->client->getResponse();
    }

    public function validTaskDataProvider(): array
    {
        return [
            'a new valid task' => [
                'This is a content of a new task',
                false
            ],
            'a new completed valid task' => [
                'This is a content of a new task',
                true
            ],
        ];
    }

    public function invalidTaskDataProvider(): array
    {
        return [
            'a new invalid task with empty content' => [
                '',
                false
            ],
            'a new invalid task with empty content and undefined status' => [
                '',
                null
            ],
            'a new invalid task with a huge content' => [
                str_repeat('some text ', 8192),
                true
            ],
        ];
    }

}