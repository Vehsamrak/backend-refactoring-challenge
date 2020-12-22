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
    private const URL = '/job';

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
        $this->requestGet(self::URL, $parameters);

        $this->assertResponseCode(Response::HTTP_OK);
        $this->assertJobsCount($jobsCount);
        $this->assertJobCategory($categoryId);
        $this->assertZipcode($zipcodeId);
    }

    /**
     * @test
     */
    public function getJobId_GivenExistingJobId_ReturnsJob(): void
    {
        $jobId = $this->fetchOneExistingJobId();
        $url = sprintf('%s/%s', self::URL, $jobId);

        $this->requestGet($url);

        $this->assertResponseCode(Response::HTTP_OK);
    }

    /**
     * @test
     */
    public function getJobId_GivenUnexistingJobId_ReturnsNotFoundError(): void
    {
        $url = sprintf('%s/%s', self::URL, JobFixtures::UNEXISTING_JOB_ID);

        $this->requestGet($url);

        $this->assertResponseCode(Response::HTTP_NOT_FOUND);
    }

    /**
     * @test
     * @dataProvider provideInvalidJobRequests
     * @param array $invalidJobData
     * @param array $expectedErrors
     */
    public function postJob_GivenInvalidJob_ReturnsBadRequestError(array $invalidJobData, array $expectedErrors): void
    {
        $this->requestPost(self::URL, $invalidJobData);

        $this->assertResponseCode(Response::HTTP_BAD_REQUEST);
        $this->assertErrors($expectedErrors);
    }

    /**
     * @test
     */
    public function postJob_GivenValidJob_NewJobCreated(): void
    {
        $existingJobsCount = $this->countExistingJobs();

        $this->requestPost(self::URL, $this->createValidJobData());

        $this->assertResponseCode(Response::HTTP_CREATED);
        $this->assertSame($existingJobsCount + 1, $this->countExistingJobs());
    }

    /**
     * @test
     */
    public function putJob_GivenUnexistingJob_ReturnsNotFoundError(): void
    {
        $url = sprintf('%s/%s', self::URL, JobFixtures::UNEXISTING_JOB_ID);

        $this->requestPut($url, $this->createValidJobData());

        $this->assertResponseCode(Response::HTTP_NOT_FOUND);
    }

    /**
     * @test
     */
    public function putJob_GivenExistingJob_ExistingJobUpdated(): void
    {
        $job = $this->fetchOneExistingJob();
        $jobId = $job['id'];
        $url = sprintf('%s/%s', self::URL, $jobId);
        $updatedJobData = ['title' => 'updated'] + $job;

        $this->requestPut($url, $updatedJobData);

        $this->assertResponseCode(Response::HTTP_OK);
        $this->assertArraySubset($updatedJobData, $this->fetchOneExistingJob($jobId));
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

    private function fetchOneExistingJob(?string $id = null): array
    {
        if (null === $id) {
            $this->requestGet(self::URL);
        } else {
            $this->requestGet(sprintf('%s/%s', self::URL, $id));
        }

        $jobs = json_decode($this->getResponseContent(), true);

        return $jobs[0] ?? $jobs;
    }

    private function fetchOneExistingJobId(): string
    {
        return $this->fetchOneExistingJob()['id'];
    }

    private function countExistingJobs(): int
    {
        $this->requestGet(self::URL);
        $jobs = json_decode($this->getResponseContent(), true);

        return count($jobs);
    }

    private function assertJobsCount(int $jobsCount): void
    {
        $responseContent = $this->getResponseContent();
        $responseData = json_decode($responseContent, true);

        $this->assertCount($jobsCount, $responseData);
    }

    private function assertJobCategory(?int $categoryId): void
    {
        if (null === $categoryId) {
            return;
        }

        $responseContent = $this->getResponseContent();
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

        $responseContent = $this->getResponseContent();
        $responseData = json_decode($responseContent, true);

        foreach ($responseData as $job) {
            $this->assertSame($job['zipcodeId'], $zipcodeId);
        }
    }
}
