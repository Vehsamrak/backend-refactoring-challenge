<?php

declare(strict_types=1);

namespace AppBundle\Tests\Controller;

use Symfony\Component\HttpFoundation\Response;

/**
 * @group functional
 */
class JobControllerTest extends AbstractControllerTest
{
    /**
     * @var array
     */
    private $defaultJob;

    public function setUp(): void
    {
        parent::setUp();
        $this->loadServiceFixtures();
        $this->loadZipcodeFixtures();
        $this->loadJobFixtures();
        $this->defaultJob = [
            'serviceId' => 804040,
            'zipcodeId' => '10115',
            'title' => 'title',
            'description' => 'decription',
            'dateToBeDone' => '2018-11-11',
        ];
    }

    /**
     * @test
     */
    public function getAllJobs(): void
    {
        $this->client->request('GET', '/job');

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @test
     */
    public function getOneJobFound(): void
    {
        // TODO[petr]: implement this
    }

    /**
     * @test
     */
    public function getOneJobNotFound(): void
    {
        // TODO[petr]: implement this
    }

    /**
     * @test
     */
    public function postInvalidJobReturnsBadRequest(): void
    {
        $this->defaultJob['title'] = '';

        $this->client->request(
            'POST',
            '/job',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($this->defaultJob)
        );

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @test
     */
    public function postJobWithServiceNotFoundReturnsBadRequest(): void
    {
        $this->defaultJob['serviceId'] = 12345;

        $this->client->request(
            'POST',
            '/job',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($this->defaultJob)
        );

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @test
     */
    public function postJobWithZipcodeNotFoundReturnsBadRequest(): void
    {
        $this->defaultJob['zipcodeId'] = '12345';

        $this->client->request(
            'POST',
            '/job',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($this->defaultJob)
        );

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @test
     */
    public function postValidJobNewJobIsCreated(): void
    {
        $this->client->request(
            'POST',
            '/job',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($this->defaultJob)
        );

        $this->assertEquals(Response::HTTP_CREATED, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @test
     */
    public function putWithNotFoundJobReturnsNotFound(): void
    {
        $this->client->request(
            'PUT',
            '/job/1',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($this->defaultJob)
        );

        $this->assertEquals(Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @test
     */
    public function putWithValidJobReturnsNotFound(): void
    {
        $id = $this->getFirstJobId();
        $this->client->request(
            'PUT',
            '/job/' . $id,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($this->defaultJob)
        );

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    private function getFirstJobId(): ?string
    {
        $this->client->request('GET', '/job');
        $allJobs = json_decode($this->client->getResponse()->getContent(), true);

        // TODO[petr]: handle "not found" behaviour
        $id = $allJobs[0]['id'] ?? null;

        return $id;
    }
}
