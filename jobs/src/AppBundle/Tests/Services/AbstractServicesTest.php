<?php

declare(strict_types=1);

namespace AppBundle\Tests\Services;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class AbstractServicesTest extends WebTestCase
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    public function setUp()
    {
        $this->entityManager = $this->getMockBuilder(EntityManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
    }
}
