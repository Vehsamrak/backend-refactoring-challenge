<?php

declare(strict_types=1);

namespace AppBundle\Tests\Controller;

use AppBundle\Tests\Controller\EntityFixtures\JobCategoryFixtures;
use Symfony\Component\HttpFoundation\Response;

/**
 * @group functional
 */
class JobCategoryControllerTest extends AbstractControllerTest
{
    private const FIXTURE_PATH = __DIR__.'/ResponseFixtures';
    private const FIXTURE_PATH_ALL_CATEGORIES = self::FIXTURE_PATH.'/allJobCategories.json';
    private const FIXTURE_PATH_ONE_CATEGORY = self::FIXTURE_PATH.'/oneJobCategory.json';
    private const URL = '/category';

    public function setUp(): void
    {
        parent::setUp();
        $this->loadJobCategoryFixtures();
    }

    /**
     * @test
     */
    public function getCategories_GivenNoParameters_ReturnsAllCategories(): void
    {
        $expected = trim(file_get_contents(self::FIXTURE_PATH_ALL_CATEGORIES));

        $this->requestGet(self::URL);

        $this->assertResponseCode(Response::HTTP_OK);
        $this->assertSame($expected, $this->getResponseContents());
    }

    /**
     * @test
     */
    public function getCategoryId_GivenExistingCategoryId_CategoryReturned(): void
    {
        $expected = trim(file_get_contents(self::FIXTURE_PATH_ONE_CATEGORY));
        $url = sprintf('%s/%d', self::URL, JobCategoryFixtures::EXISTING_JOB_CATEGORY_ID_1);

        $this->requestGet($url);

        $this->assertResponseCode(Response::HTTP_OK);
        $this->assertSame($expected, $this->getResponseContents());
    }

    /**
     * @test
     */
    public function getCategoryId_GivenUnexistingCategoryId_NotFoundErrorReturned(): void
    {
        $url = sprintf('%s/%d', self::URL, JobCategoryFixtures::UNEXISTING_JOB_CATEGORY_ID);

        $this->requestGet($url);

        $this->assertResponseCode(Response::HTTP_NOT_FOUND);
    }

    /**
     * @test
     * @dataProvider provideInvalidCategories
     * @param array $jobCategory
     * @param array $expectedErrors
     */
    public function postCategory_GivenInvalidCategory_BadRequestReturned(
        array $jobCategory,
        array $expectedErrors
    ): void {
        $this->requestPost(self::URL, $jobCategory);

        $this->assertResponseCode(Response::HTTP_BAD_REQUEST);
        $this->assertErrors($expectedErrors);
    }

    /**
     * @test
     */
    public function postCategory_GivenValidCategory_CreatedCategoryReturned(): void
    {
        $existingCategoriesCount = $this->countExistingCategories();
        $jobCategory = $this->createValidJobCategoryData();

        $this->requestPost(self::URL, $jobCategory);

        $this->assertResponseCode(Response::HTTP_CREATED);
        $this->assertSame($existingCategoriesCount + 1, $this->countExistingCategories());
    }

    public function provideInvalidCategories(): array
    {
        return [
            'category already exists' => [
                ['id' => JobCategoryFixtures::EXISTING_JOB_CATEGORY_ID_1] + $this->createValidJobCategoryData(),
                ['id' => sprintf('The id "%s" already exists.', JobCategoryFixtures::EXISTING_JOB_CATEGORY_ID_1)],
            ],
            'empty name' => [
                ['name' => ''] + $this->createValidJobCategoryData(),
                ['name' => 'The name should not be blank.'],
            ],
            'name is least 5 characters' => [
                ['name' => $this->createStringWithLength(4)] + $this->createValidJobCategoryData(),
                ['name' => 'The name must have at least 5 characters.'],
            ],
            'name is greater then 255 characters' => [
                ['name' => $this->createStringWithLength(256)] + $this->createValidJobCategoryData(),
                ['name' => 'The name must have less than 256 characters.'],
            ],
        ];
    }

    private function countExistingCategories(): int
    {
        $this->requestGet(self::URL);
        $jobs = json_decode($this->getResponseContents(), true);

        return count($jobs);
    }

    private function createValidJobCategoryData(): array
    {
        return [
            'id' => JobCategoryFixtures::UNEXISTING_JOB_CATEGORY_ID,
            'name' => JobCategoryFixtures::UNEXISTING_JOB_CATEGORY_NAME,
        ];
    }
}
