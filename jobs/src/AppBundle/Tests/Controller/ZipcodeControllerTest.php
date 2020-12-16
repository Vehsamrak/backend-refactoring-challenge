<?php

declare(strict_types=1);

namespace AppBundle\Tests\Controller;

use Symfony\Component\HttpFoundation\Response;

/**
 * @group functional
 */
class ZipcodeControllerTest extends AbstractControllerTest
{
    private const FIXTURE_PATH = __DIR__.'/ResponseFixtures/zipcodes.json';

    public function setUp(): void
    {
        parent::setUp();
        $this->loadZipcodeFixtures();
    }

    /**
     * @test
     */
    public function getAllZipcodes(): void
    {
        $expected = file_get_contents(self::FIXTURE_PATH);

        $this->client->request('GET', '/zipcode');

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertEquals($expected, $this->client->getResponse()->getContent());
    }

    /**
     * @test
     */
    public function getOneZipcodeFound(): void
    {
        $expected = '{"id":"01623","city":"Lommatzsch"}';

        $this->client->request('GET', '/zipcode/01623');

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertEquals($expected, $this->client->getResponse()->getContent());
    }

    /**
     * @test
     */
    public function getOneZipcodeNotFound(): void
    {
        $this->client->request('GET', '/zipcode/1');

        $this->assertEquals(Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @test
     */
    public function postZipcodeRepeatedReturnsBadRequest(): void
    {
        $this->client->request(
            'POST',
            '/zipcode',
            [],
            [],
            ['CONTENT-TYPE' => 'application/json'],
            '{"id": "01623", "city": "Lommatzsch"}'
        );

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @test
     */
    public function postInvalidZipcodeReturnsBadRequest(): void
    {
        $this->client->request(
            'POST',
            '/zipcode',
            [],
            [],
            ['CONTENT-TYPE' => 'application/json'],
            '{"id": "123", "city": ""}'
        );

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @test
     */
    public function postValidZipcodeReturnsCreated(): void
    {
        $this->client->request(
            'POST',
            '/zipcode',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{"id": "12345", "city": "Valid city"}'
        );

        $this->assertEquals(Response::HTTP_CREATED, $this->client->getResponse()->getStatusCode());
    }
}
