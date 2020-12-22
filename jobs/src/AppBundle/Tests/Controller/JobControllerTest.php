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
    private const INVALID_TITLE_TOO_SHORT = '1234';
    private const INVALID_TITLE_TOO_LONG = '1234567890123456789012345678901234567890123456789012';

    public function setUp(): void
    {
        parent::setUp();
        $this->loadJobCategoryFixtures();
        $this->loadZipcodeFixtures();
        $this->loadJobFixtures();
    }

    /**
     * @test
     */
    public function getJob_GivenNoParameters_ReturnsAllJobsWithDefaulLimit(): void
    {
        $this->client->request('GET', '/job');

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @test
     */
    public function getJob_GivenSearchParameters_ReturnsOnlyMatchedJobs(): void
    {
        // TODO[petr]: implement this
    }

    /**
     * @test
     */
    public function getJobId_GivenExistingJobId_ReturnsJob(): void
    {
        $jobId = $this->fetchExistingJobId();

        $this->client->request('GET', sprintf('/job/%s', $jobId));

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @test
     */
    public function getJobId_GivenUnexistingJobId_ReturnsNotFoundError(): void
    {
        $this->client->request('GET', sprintf('/job/%s', JobFixtures::UNEXISTING_JOB_ID));

        $this->assertEquals(Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @test
     * @dataProvider provideInvalidJobRequests
     * @param array $invalidJobData
     * @param array $expectedErrors
     */
    public function postJob_GivenInvalidJob_ReturnsBadRequestError(array $invalidJobData, array $expectedErrors): void
    {
        // TODO[petr]: move requestPost, requestGet, requestPut methods in abstract controller
        $this->client->request(
            'POST',
            '/job',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($invalidJobData)
        );

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
        $this->assertErrors($expectedErrors);
    }

    /**
     * @test
     */
    public function postJob_GivenUnexistingCategory_ReturnsBadRequestError(): void
    {
        $validJob = [['category' => JobCategoryFixtures::UNEXISTING_JOB_CATEGORY_ID] + $this->createValidJobData()];

        $this->client->request(
            'POST',
            '/job',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($validJob)
        );

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @test
     */
    public function postJob_GivenUnexistingZipcode_ReturnsBadRequestError(): void
    {
        $validJob = [['zipcode' => ZipcodeFixtures::UNEXISTING_ZIPCODE_ID] + $this->createValidJobData()];

        $this->client->request(
            'POST',
            '/job',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($validJob)
        );

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @test
     */
    public function postJob_GivenValidJob_NewJobCreated(): void
    {
        $this->client->request(
            'POST',
            '/job',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($this->createValidJobData())
        );

        $this->assertEquals(Response::HTTP_CREATED, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @test
     */
    public function putJob_GivenUnexistingJob_ReturnsNotFoundError(): void
    {
        $this->client->request(
            'PUT',
            sprintf('/job/%s', JobFixtures::UNEXISTING_JOB_ID),
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($this->createValidJobData())
        );

        $this->assertEquals(Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @test
     */
    public function putJob_GivenExistingJob_ExistingJobUpdated(): void
    {
        $jobId = $this->fetchExistingJobId();

        $this->client->request(
            'PUT',
            sprintf('/job/%s', $jobId),
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($this->createValidJobData())
        );

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    private function fetchExistingJobId(): string
    {
        $this->client->request('GET', '/job');
        $allJobs = json_decode($this->client->getResponse()->getContent(), true);

        return $allJobs[0]['id'];
    }

    protected function createValidJobData(): array
    {
        return [
            'categoryId' => JobCategoryFixtures::EXISTING_JOB_CATEGORY_ID,
            'zipcodeId' => ZipcodeFixtures::EXISTING_ZIPCODE_ID,
            'title' => 'title',
            'description' => 'decription',
            'dateToBeDone' => '2018-11-11',
        ];
    }

    public function provideInvalidJobRequests(): array
    {
        return [
            'empty category' => [
                ['categoryId' => null] + $this->createValidJobData(),
                ['categoryId' => 'Job category should not be blank'],
            ],
            'unexisting category' => [
                ['categoryId' => JobCategoryFixtures::UNEXISTING_JOB_CATEGORY_ID] + $this->createValidJobData(),
                [
                    'categoryId' => sprintf(
                        'Job category "%d" was not found',
                        JobCategoryFixtures::UNEXISTING_JOB_CATEGORY_ID
                    ),
                ],
            ],
            'empty zipcode' => [
                ['zipcodeId' => null] + $this->createValidJobData(),
                ['zipcodeId' => 'Zipcode should not be blank'],
            ],
            'unexisting zipcode' => [
                ['zipcodeId' => ZipcodeFixtures::UNEXISTING_ZIPCODE_ID] + $this->createValidJobData(),
                ['zipcodeId' => sprintf('Zipcode "%d" was not found', ZipcodeFixtures::UNEXISTING_ZIPCODE_ID)],
            ],
            'empty title' => [
                ['title' => null] + $this->createValidJobData(),
                ['title' => 'Title should not be blank'],
            ],
            'too short title' => [
                ['title' => self::INVALID_TITLE_TOO_SHORT] + $this->createValidJobData(),
                ['title' => 'The title must have more than 4 characters'],
            ],
            'too long title' => [
                ['title' => self::INVALID_TITLE_TOO_LONG] + $this->createValidJobData(),
                ['title' => 'The title must have less than 51 characters'],
            ],
        ];
    }

    private function assertErrors(array $expectedErrors): void
    {
        $responseContent = $this->client->getResponse()->getContent();
        $this->assertJson($responseContent);

        $responseData = json_decode($responseContent, true);
        $responseDataErrors = $responseData['errors'] ?? [];
        $this->assertArraySubset(
            $expectedErrors,
            $responseDataErrors,
            true,
            sprintf('Actual errors: %s', json_encode($responseDataErrors))
        );
    }
}
