<?php

declare(strict_types=1);

namespace AppBundle\Tests\Controller\EntityFixtures;

use AppBundle\Entity\Zipcode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ZipcodeFixtures extends Fixture
{
    // TODO[petr]: make ids integers
    public const EXISTING_ZIPCODE_ID_1 = '10115';
    public const EXISTING_ZIPCODE_ID_2 = '32457';
    public const UNEXISTING_ZIPCODE_ID = '12345';
    public const EXISTING_ZIPCODE_CITY_1 = 'Berlin';
    public const EXISTING_ZIPCODE_CITY_2 = 'Porta Westfalica';

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager): void
    {
        $manager->persist(new Zipcode(self::EXISTING_ZIPCODE_ID_1, self::EXISTING_ZIPCODE_CITY_1));
        $manager->persist(new Zipcode(self::EXISTING_ZIPCODE_ID_2, self::EXISTING_ZIPCODE_CITY_2));
        $manager->persist(new Zipcode('01623', 'Lommatzsch'));
        $manager->persist(new Zipcode('21521', 'Hamburg'));
        $manager->persist(new Zipcode('06895', 'Bülzig'));
        $manager->persist(new Zipcode('01612', 'Diesbar-Seußlitz'));
        $manager->flush();
    }
}
