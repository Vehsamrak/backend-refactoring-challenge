<?php

declare(strict_types=1);

namespace AppBundle\Tests\Controller;

use AppBundle\Tests\Controller\EntityFixtures\JobCategoryFixtures;
use AppBundle\Tests\Controller\EntityFixtures\JobFixtures;
use AppBundle\Tests\Controller\EntityFixtures\ZipcodeFixtures;
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
        $this->loadJobCategoryFixtures();
        $this->loadZipcodeFixtures();
        $this->loadJobFixtures();
        $this->defaultJob = [
            'categoryId' => JobCategoryFixtures::EXISTING_JOB_CATEGORY_ID,
            'zipcodeId' => ZipcodeFixtures::EXISTING_ZIPCODE_ID,
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
        $this->defaultJob['categoryId'] = JobCategoryFixtures::UNEXISTING_JOB_CATEGORY_ID;

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
        $this->defaultJob['zipcodeId'] = ZipcodeFixtures::UNEXISTING_ZIPCODE_ID;

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
            sprintf('/job/%s', JobFixtures::UNEXISTING_JOB_ID),
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
        $jobId = $this->fetchFirstJobId();

        $this->client->request(
            'PUT',
            sprintf('/job/%s', $jobId),
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($this->defaultJob)
        );

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    private function fetchFirstJobId(): string
    {
        $this->client->request('GET', '/job');
        $allJobs = json_decode($this->client->getResponse()->getContent(), true);

        return $allJobs[0]['id'];
    }
}
