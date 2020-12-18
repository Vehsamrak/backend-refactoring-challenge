<?php

declare(strict_types=1);

namespace AppBundle\Tests\Services;

use AppBundle\Entity\JobCategory;
use AppBundle\Repository\JobCategoryRepository;
use AppBundle\Services\JobCategory\Service;

/**
 * @group unit
 */
class ServiceTest extends AbstractServicesTest
{
    /**
     * @var JobCategoryRepository
     */
    private $jobCategoryRepository;

    /**
     * @var JobCategory
     */
    protected $defaultJobCategoryEntity;

    public function setUp()
    {
        parent::setUp();
        $this->jobCategoryRepository =
            $this->getMockBuilder(JobCategoryRepository::class)
                 ->disableOriginalConstructor()
                 ->setMethods(['findAll', 'find'])
                 ->getMock();

        $this->defaultJobCategoryEntity = new JobCategory(1, 'service');
    }

    public function testFindAllWithoutValueReturnsEmptyArray()
    {
        $this->jobCategoryRepository
            ->expects($this->once())
            ->method('findAll')
            ->will($this->returnValue([]));

        $service = new Service($this->jobCategoryRepository, $this->entityManager);
        $this->assertEmpty($service->findAll());
    }

    public function testFindAllWithServicesFoundReturnsArrayWithServices()
    {
        $this->jobCategoryRepository
            ->expects($this->once())
            ->method('findAll')
            ->will($this->returnValue([$this->defaultJobCategoryEntity]));

        $service = new Service($this->jobCategoryRepository, $this->entityManager);
        $this->assertEquals([$this->defaultJobCategoryEntity], $service->findAll());
    }

    public function testFindWhenServiceIsNotFoundReturnsNull()
    {
        $service = new Service($this->jobCategoryRepository, $this->entityManager);
        $this->assertNull($service->find(1));
    }

    public function testFindWhenServiceIsFoundReturnsService()
    {
        $this->jobCategoryRepository
            ->expects($this->once())
            ->method('find')
            ->will($this->returnValue($this->defaultJobCategoryEntity))
            ->with(1);

        $service = new Service($this->jobCategoryRepository, $this->entityManager);
        $this->assertEquals($this->defaultJobCategoryEntity, $service->find(1));
    }

    /**
     * @expectedException Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     * @expectedExceptionMessage name: This value should not be blank.
     */
    public function testCreateWithInvalidServiceThrowsBadRequestHttpException()
    {
        $this->jobCategoryRepository
            ->expects($this->never())
            ->method('find');
        $this->entityManager
            ->expects($this->never())
            ->method('persist');
        $this->entityManager
            ->expects($this->never())
            ->method('flush');

        $service = new Service($this->jobCategoryRepository, $this->entityManager);
        $service->create(new JobCategory(1, ''));
    }

    /**
     * @expectedException Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     * @expectedExceptionMessage Resource '1' already exists
     */
    public function testCreateWithDuplicatedServiceThrowsBadRequestHttpException()
    {
        $this->jobCategoryRepository
            ->expects($this->once())
            ->method('find')
            ->will($this->returnValue($this->defaultJobCategoryEntity))
            ->with(1);
        $this->entityManager
            ->expects($this->never())
            ->method('persist');
        $this->entityManager
            ->expects($this->never())
            ->method('flush');

        $service = new Service($this->jobCategoryRepository, $this->entityManager);
        $service->create($this->defaultJobCategoryEntity);
    }

    public function testCreateWithValidServiceReturnsPersistedService()
    {
        $this->jobCategoryRepository
            ->expects($this->once())
            ->method('find')
            ->will($this->returnValue(null))
            ->with(1);
        $this->entityManager
            ->expects($this->once())
            ->method('persist')
            ->with($this->defaultJobCategoryEntity);
        $this->entityManager
            ->expects($this->once())
            ->method('flush');

        $service = new Service($this->jobCategoryRepository, $this->entityManager);
        $service->create($this->defaultJobCategoryEntity);
    }
}
