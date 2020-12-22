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
    public function setUp(): void
    {
        parent::setUp();
        $this->loadJobFixtures();
    }

    /**
     * @test
     * @dataProvider provideSearchParameters
     * @param array       $parameters
     * @param int|null    $categoryId
     * @param string|null $zipcodeId
     * @param int         $jobsCount
     */
    public function getJob_GivenSearchParameters_ReturnsOnlyMatchedJobs(
        array $parameters,
        ?int $categoryId,
        ?string $zipcodeId,
        int $jobsCount
    ): void {
        $this->client->request('GET', '/job', $parameters);

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
        $this->assertJobsCount($jobsCount);
        $this->assertJobCategory($categoryId);
        $this->assertZipcode($zipcodeId);
    }

    /**
     * @test
     */
    public function getJobId_GivenExistingJobId_ReturnsJob(): void
    {
        $jobId = $this->fetchExistingJobId();

        $this->client->request('GET', sprintf('/job/%s', $jobId));

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    /**
     * @test
     */
    public function getJobId_GivenUnexistingJobId_ReturnsNotFoundError(): void
    {
        $this->client->request('GET', sprintf('/job/%s', JobFixtures::UNEXISTING_JOB_ID));

        $this->assertSame(Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
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

        $this->assertSame(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
        $this->assertErrors($expectedErrors);
    }

    /**
     * @test
     */
    public function postJob_GivenValidJob_NewJobCreated(): void
    {
        $existingJobsCount = $this->countExistingJobs();

        $this->client->request(
            'POST',
            '/job',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($this->createValidJobData())
        );

        $this->assertSame(Response::HTTP_CREATED, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
        $this->assertSame($existingJobsCount + 1, $this->countExistingJobs());
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

        $this->assertSame(Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    /**
     * @test
     */
    public function putJob_GivenExistingJob_ExistingJobUpdated(): void
    {
        $job = $this->fetchExistingJob();
        $jobId = $job['id'];
        $updatedJobData = ['title' => 'updated'] + $job;

        $this->client->request(
            'PUT',
            sprintf('/job/%s', $jobId),
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($updatedJobData)
        );

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
        $this->assertArraySubset($updatedJobData, $this->fetchExistingJob($jobId));
    }

    public function provideInvalidJobRequests(): array
    {
        return [
            'empty category' => [
                ['categoryId' => null] + $this->createValidJobData(),
                ['categoryId' => 'The categoryId should not be blank.'],
            ],
            'unexisting category' => [
                ['categoryId' => JobCategoryFixtures::UNEXISTING_JOB_CATEGORY_ID] + $this->createValidJobData(),
                [
                    'categoryId' => sprintf(
                        'The category "%d" was not found.',
                        JobCategoryFixtures::UNEXISTING_JOB_CATEGORY_ID
                    ),
                ],
            ],
            'empty zipcode' => [
                ['zipcodeId' => null] + $this->createValidJobData(),
                ['zipcodeId' => 'The zipcodeId should not be blank.'],
            ],
            'unexisting zipcode' => [
                ['zipcodeId' => ZipcodeFixtures::UNEXISTING_ZIPCODE_ID] + $this->createValidJobData(),
                ['zipcodeId' => sprintf('The zipcode "%d" was not found.', ZipcodeFixtures::UNEXISTING_ZIPCODE_ID)],
            ],
            'empty title' => [
                ['title' => null] + $this->createValidJobData(),
                ['title' => 'The title should not be blank.'],
            ],
            'too short title' => [
                ['title' => $this->createStringWithLength(4)] + $this->createValidJobData(),
                ['title' => 'The title must have more than 4 characters.'],
            ],
            'too long title' => [
                ['title' => $this->createStringWithLength(51)] + $this->createValidJobData(),
                ['title' => 'The title must have less than 51 characters.'],
            ],
        ];
    }

    public function provideSearchParameters(): array
    {
        return [
            'no parameters' => [[], null, null, 2],
            'limit 0' => [['limit' => 0], null, null, 0],
            'limit 1' => [['limit' => 1], null, null, 1],
            'limit 1 offset 100' => [['limit' => 1, 'offset' => 100], null, null, 0],
            'zipcode' => [
                ['zipcodeId' => ZipcodeFixtures::EXISTING_ZIPCODE_ID_1],
                null,
                ZipcodeFixtures::EXISTING_ZIPCODE_ID_1,
                1,
            ],
            'category' => [
                ['categoryId' => JobCategoryFixtures::EXISTING_JOB_CATEGORY_ID_1],
                JobCategoryFixtures::EXISTING_JOB_CATEGORY_ID_1,
                null,
                1,
            ],
        ];
    }

    protected function createValidJobData(): array
    {
        return [
            'categoryId' => JobCategoryFixtures::EXISTING_JOB_CATEGORY_ID_1,
            'zipcodeId' => ZipcodeFixtures::EXISTING_ZIPCODE_ID_1,
            'title' => 'title',
            'description' => 'decription',
            'dateToBeDone' => '2018-11-11',
        ];
    }

    private function fetchExistingJob(?string $id = null): array
    {
        if (null === $id) {
            $this->client->request('GET', '/job');
        } else {
            $this->client->request('GET', '/job/'.$id);
        }

        $jobs = json_decode($this->client->getResponse()->getContent(), true);

        return $jobs[0] ?? $jobs;
    }

    private function countExistingJobs(): int
    {
        $this->client->request('GET', '/job');
        $jobs = json_decode($this->client->getResponse()->getContent(), true);

        return count($jobs);
    }

    private function fetchExistingJobId(): string
    {
        return $this->fetchExistingJob()['id'];
    }

    private function assertJobsCount(int $jobsCount): void
    {
        $responseContent = $this->client->getResponse()->getContent();
        $responseData = json_decode($responseContent, true);

        $this->assertCount($jobsCount, $responseData);
    }

    private function assertJobCategory(?int $categoryId): void
    {
        if (null === $categoryId) {
            return;
        }

        $responseContent = $this->client->getResponse()->getContent();
        $responseData = json_decode($responseContent, true);

        foreach ($responseData as $job) {
            $this->assertSame($job['categoryId'], $categoryId);
        }
    }

    private function assertZipcode(?string $zipcodeId): void
    {
        if (null === $zipcodeId) {
            return;
        }

        $responseContent = $this->client->getResponse()->getContent();
        $responseData = json_decode($responseContent, true);

        foreach ($responseData as $job) {
            $this->assertSame($job['zipcodeId'], $zipcodeId);
        }
    }
}
