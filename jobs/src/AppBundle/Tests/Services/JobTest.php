<?php

declare(strict_types=1);

namespace AppBundle\Tests\Services;

use AppBundle\Entity\Job as JobEntity;
use AppBundle\Repository\JobRepository;
use AppBundle\Services\JobCategory\Service;
use AppBundle\Services\Zipcode\Zipcode;
use DateTime;

/**
 * @group unit
 */
class JobTest extends AbstractServicesTest
{
    /**
     * @var JobRepository
     */
    private $repository;

    /**
     * @var Service
     */
    private $service;

    /**
     * @var Zipcode
     */
    private $zipcode;

    /**
     * @var JobEntity
     */
    private $defaultEntity;

    /**
     * @var JobEntity
     */
    private $persistedEntity;

    public function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->getMockBuilder(JobRepository::class)
            ->disableOriginalConstructor()
            ->setMethods(['findAll', 'find'])
            ->getMock();

        $this->service = $this->getMockBuilder(Service::class)
            ->disableOriginalConstructor()
            ->setMethods(['find'])
            ->getMock();

        $this->zipcode = $this->getMockBuilder(Zipcode::class)
            ->disableOriginalConstructor()
            ->setMethods(['find'])
            ->getMock();

        $this->defaultEntity = new JobEntity(
            802031,
            '01621',
            'Job to be done',
            'description',
            new DateTime('2018-11-11')
        );
        $this->persistedEntity = new JobEntity(
            802031,
            '01621',
            'Job to be done',
            'description',
            new DateTime('2018-11-11')
        );
    }

    /**
     * @expectedException Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     * @expectedExceptionMessage zipcode_id: This value should have exactly 5 characters., title: The title must more than 4 characters
     */
    public function testCreateJobWithInvalidDataThrowsBadRequestHttpException()
    {
        // TODO[petr]: move to functional test
    }

    /**
     * @expectedException Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     * @expectedExceptionMessage Service '802031' was not found
     */
    public function testCreateJobWithServiceNotFoundThrowsBadRequestHttpException()
    {
        // TODO[petr]: move to functional test
    }

    /**
     * @expectedException Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     * @expectedExceptionMessage Zipcode '12345' was not found
     */
    public function testCreateJobWithZipcodeNotFoundThrowsBadRequestHttpException()
    {
        // TODO[petr]: move to functional test
    }

    public function testCreateJobWithValidJobReturnsPersistedJob()
    {
        // TODO[petr]: move to functional test
    }

    /**
     * @expectedException Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @expectedExceptionMessage The resource 'a1c59e8f-ca88-11e8-94bd-0242ac130005
     */
    public function testUpdateJobWithNotFoundThrowsBadRequestHttpException()
    {
        // TODO[petr]: move to functional test
    }

    public function testUpdateJobValidReturnsPersistedJob()
    {
        // TODO[petr]: move to functional test
    }
}
