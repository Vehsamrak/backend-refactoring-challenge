<?php

declare(strict_types=1);

namespace AppBundle\Tests\Controller;

use Symfony\Component\HttpFoundation\Response;

/**
 * @group functional
 */
class JobCategoryControllerTest extends AbstractControllerTest
{
    private const FIXTURE_PATH = __DIR__.'/ResponseFixtures';
    private const FIXTURE_PATH_ALL_CATEGORIES = self::FIXTURE_PATH.'/allJobCategories.json';
    private const FIXTURE_PATH_ONE_CATEGORY = self::FIXTURE_PATH.'/oneJobCategory.json';

    public function setUp(): void
    {
        parent::setUp();
        $this->loadServiceFixtures();
    }

    /**
     * @test
     */
    public function getAllCategories(): void
    {
        $expected = trim(file_get_contents(self::FIXTURE_PATH_ALL_CATEGORIES));

        $this->client->request('GET', '/category');

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertEquals($expected, $this->client->getResponse()->getContent());
    }

    /**
     * @test
     */
    public function getOneCategoryFound(): void
    {
        $expected = trim(file_get_contents(self::FIXTURE_PATH_ONE_CATEGORY));

        $this->client->request('GET', '/category/411070');

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertEquals($expected, $this->client->getResponse()->getContent());
    }

    /**
     * @test
     */
    public function getOneCategoryNotFound(): void
    {
        $this->client->request('GET', '/category/1');

        $this->assertEquals(Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @test
     */
    public function postCategoryRepeatedReturnsBadRequest(): void
    {
        $this->client->request(
            'POST',
            '/category',
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
    public function postInvalidCategoryReturnsBadRequest(): void
    {
        $this->client->request(
            'POST',
            '/category',
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
    public function postValidCategoryReturnsCreated(): void
    {
        $this->client->request(
            'POST',
            '/category',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{"id": 123, "name": "New Service"}'
        );

        $this->assertEquals(Response::HTTP_CREATED, $this->client->getResponse()->getStatusCode());
    }
}
