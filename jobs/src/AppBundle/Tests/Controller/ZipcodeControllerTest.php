<?php

declare(strict_types=1);

namespace AppBundle\Tests\Controller;

use AppBundle\Tests\Controller\EntityFixtures\ZipcodeFixtures;
use Symfony\Component\HttpFoundation\Response;

/**
 * @group functional
 */
class ZipcodeControllerTest extends AbstractControllerTest
{
    private const FIXTURE_PATH = __DIR__.'/ResponseFixtures/zipcodes.json';
    private const URL = '/zipcode';

    public function setUp(): void
    {
        parent::setUp();
        $this->loadZipcodeFixtures();
    }

    /**
     * @test
     */
    public function getZipcode_GivenNoParameters_MustReturnAllZipcodes(): void
    {
        $expectedZipcodes = file_get_contents(self::FIXTURE_PATH);

        $this->client->request('GET', self::URL);

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
        $this->assertSame($expectedZipcodes, $this->client->getResponse()->getContent());
    }

    /**
     * @test
     */
    public function getZipcodeId_GivenExistingZipcode_ReturnsOneZipcode(): void
    {
        $expectedZipcode = [
            'id' => ZipcodeFixtures::EXISTING_ZIPCODE_ID_1,
            'city' => ZipcodeFixtures::EXISTING_ZIPCODE_CITY_1,
        ];
        $url = sprintf('%s/%s', self::URL, ZipcodeFixtures::EXISTING_ZIPCODE_ID_1);

        $this->client->request('GET', $url);

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
        $this->assertSame(json_encode($expectedZipcode), $this->client->getResponse()->getContent());
    }

    /**
     * @test
     */
    public function getZipcodeId_GivenUnexistingZipcode_ReturnsNotFoundError(): void
    {
        $url = sprintf('%s/%s', self::URL, ZipcodeFixtures::UNEXISTING_ZIPCODE_ID);

        $this->client->request('GET', $url);

        $this->assertSame(Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    /**
     * @test
     * @dataProvider provideInvalidZipcodes
     * @param array $zipcode
     * @param array $expectedErrors
     */
    public function postZipcode_GivenInvalidZipcode_ReturnsBadRequest(array $zipcode, array $expectedErrors): void
    {
        $this->client->request(
            'POST',
            self::URL,
            [],
            [],
            ['CONTENT-TYPE' => 'application/json'],
            json_encode($zipcode)
        );

        $this->assertSame(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
        $this->assertErrors($expectedErrors);
    }

    /**
     * @test
     */
    public function postValidZipcodeReturnsCreated(): void
    {
        $existingZipcodes = $this->countExistingZipcodes();
        $zipcode = $this->createValidZipcodeData();

        $this->client->request(
            'POST',
            self::URL,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($zipcode)
        );

        $this->assertSame(Response::HTTP_CREATED, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
        $this->assertSame($existingZipcodes + 1, $this->countExistingZipcodes());
    }

    public function provideInvalidZipcodes(): array
    {
        return [
            'zipcode already exist' => [
                ['id' => ZipcodeFixtures::EXISTING_ZIPCODE_ID_1] + $this->createValidZipcodeData(),
                ['id' => sprintf('The id "%s" already exists.', ZipcodeFixtures::EXISTING_ZIPCODE_ID_1)],
            ],
            'empty id' => [
                ['id' => null] + $this->createValidZipcodeData(),
                ['id' => 'The id should not be blank.'],
            ],
            'id minimum 5 characters' => [
                ['id' => $this->createStringWithLength(4)] + $this->createValidZipcodeData(),
                ['id' => 'This value should have exactly 5 characters.'],
            ],
            'id maximum 5 characters' => [
                ['id' => $this->createStringWithLength(6)] + $this->createValidZipcodeData(),
                ['id' => 'This value should have exactly 5 characters.'],
            ],
            'empty city' => [
                ['city' => null] + $this->createValidZipcodeData(),
                ['city' => 'The city should not be blank.'],
            ],
            'city minimum 3 characters' => [
                ['city' => $this->createStringWithLength(2)] + $this->createValidZipcodeData(),
                ['city' => 'The city must have at least 3 characters.'],
            ],
            'city maximum 50 characters' => [
                ['city' => $this->createStringWithLength(51)] + $this->createValidZipcodeData(),
                ['city' => 'The city must have less than 51 characters.'],
            ],
        ];
    }

    private function createValidZipcodeData(): array
    {
        return ['id' => ZipcodeFixtures::UNEXISTING_ZIPCODE_ID, 'city' => ZipcodeFixtures::UNEXISTING_ZIPCODE_CITY];
    }

    private function countExistingZipcodes(): int
    {
        $this->client->request('GET', self::URL);
        $jobs = json_decode($this->client->getResponse()->getContent(), true);

        return count($jobs);
    }
}
