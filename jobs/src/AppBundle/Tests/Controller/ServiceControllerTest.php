<?php

declare(strict_types=1);

namespace AppBundle\Tests\Controller;

use Symfony\Component\HttpFoundation\Response;

/**
 * @group functional
 */
class ServiceControllerTest extends AbstractControllerTest
{
    private const FIXTURE_PATH = __DIR__.'/fixtures/services.json';

    public function setUp(): void
    {
        parent::setUp();
        $this->loadServiceFixtures();
    }

    /**
     * @test
     */
    public function getAllServices(): void
    {
        $expected = file_get_contents(self::FIXTURE_PATH);

        $this->client->request('GET', '/service');

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertEquals($expected, $this->client->getResponse()->getContent());
    }

    /**
     * @test
     */
    public function getOneServiceFound(): void
    {
        $expected = '{"id":411070,"name":"Fensterreinigung"}';

        $this->client->request('GET', '/service/411070');

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertEquals($expected, $this->client->getResponse()->getContent());
    }

    /**
     * @test
     */
    public function getOneServiceNotFound(): void
    {
        $this->client->request('GET', '/service/1');

        $this->assertEquals(Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @test
     */
    public function postServiceRepeatedReturnsBadRequest(): void
    {
        $this->client->request(
            'POST',
            '/service',
            [],
            [],
            ['CONTENT-TYPE' => 'application/json'],
            '{"id": 804040, "name": "Sonstige Umzugsleistungen"}'
        );

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @test
     */
    public function postInvalidServiceReturnsBadRequest(): void
    {
        $this->client->request(
            'POST',
            '/service',
            [],
            [],
            ['CONTENT-TYPE' => 'application/json'],
            '{"id": 123, "name": ""}'
        );

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @test
     */
    public function postValidServiceReturnsCreated(): void
    {
        $this->client->request(
            'POST',
            '/service',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{"id": 123, "name": "New Service"}'
        );

        $this->assertEquals(Response::HTTP_CREATED, $this->client->getResponse()->getStatusCode());
    }
}
