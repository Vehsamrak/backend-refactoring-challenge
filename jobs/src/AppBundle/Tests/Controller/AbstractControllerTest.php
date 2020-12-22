<?php

declare(strict_types=1);

namespace AppBundle\Tests\Controller;

use AppBundle\Tests\Controller\EntityFixtures\JobFixtures;
use AppBundle\Tests\Controller\EntityFixtures\JobCategoryFixtures;
use AppBundle\Tests\Controller\EntityFixtures\ZipcodeFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class AbstractControllerTest extends WebTestCase
{
    private const CONTENT_TYPE_JSON = ['Content-Type' => 'application/json'];

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var Client
     */
    private $client;

    public function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();

        $schemaTool = new SchemaTool($this->entityManager);
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();

        $schemaTool->dropSchema($metadata);
        $schemaTool->createSchema($metadata);
        $this->client = self::createClient();
    }

    protected function createStringWithLength(int $int): string
    {
        return str_repeat('x', $int);
    }

    protected function assertErrors(array $expectedErrors): void
    {
        $responseContent = $this->client->getResponse()->getContent();

        $responseData = json_decode($responseContent, true);
        $responseDataErrors = $responseData['errors'] ?? [];
        $this->assertArraySubset(
            $expectedErrors,
            $responseDataErrors,
            true,
            sprintf('Actual errors: %s', json_encode($responseDataErrors))
        );
    }

    protected function assertResponseCode(int $httpCode): void
    {
        $this->assertSame($httpCode, $this->getResponseCode());
    }

    protected function loadJobCategoryFixtures(): void
    {
        $this->load(new JobCategoryFixtures());
    }

    protected function loadZipcodeFixtures(): void
    {
        $this->load(new ZipcodeFixtures());
    }

    protected function loadJobFixtures(): void
    {
        $this->load(new JobFixtures());
    }

    private function load(Fixture $fixture): void
    {
        $fixture->load($this->entityManager);
    }

    protected function requestGet(string $url, array $parameters = []): void
    {
        $this->client->request('GET', $url, $parameters, [], self::CONTENT_TYPE_JSON);
    }

    protected function requestPost(string $url, array $parameters): void
    {
        $this->client->request('POST', $url, [], [], self::CONTENT_TYPE_JSON, json_encode($parameters));
    }

    protected function requestPut(string $url, array $parameters = []): void
    {
        $this->client->request('PUT', $url, [], [], self::CONTENT_TYPE_JSON, json_encode($parameters));
    }

    protected function getResponseContents(): string
    {
        $response = $this->client->getResponse()->getContent();
        $this->assertJson($response);

        return $response;
    }

    protected function getResponseCode(): int
    {
        return $this->client->getResponse()->getStatusCode();
    }

    public function tearDown(): void
    {
        parent::tearDown();

        $purger = new ORMPurger($this->entityManager);
        $purger->setPurgeMode(ORMPurger::PURGE_MODE_DELETE);
        $purger->purge();

        $this->entityManager->close();
        $this->entityManager = null; // avoid memory leaks
    }
}
