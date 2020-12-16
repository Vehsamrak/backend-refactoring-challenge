<?php

declare(strict_types=1);

namespace AppBundle\Tests\Controller\EntityFixtures;

use AppBundle\Entity\Zipcode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ZipcodeFixtures extends Fixture
{
    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager): void
    {
        $zipcode1 = new Zipcode('10115', 'Berlin');
        $zipcode2 = new Zipcode('32457', 'Porta Westfalica');
        $zipcode3 = new Zipcode('01623', 'Lommatzsch');
        $zipcode4 = new Zipcode('21521', 'Hamburg');
        $zipcode5 = new Zipcode('06895', 'Bülzig');
        $zipcode6 = new Zipcode('01612', 'Diesbar-Seußlitz');
        $manager->persist($zipcode1);
        $manager->persist($zipcode2);
        $manager->persist($zipcode3);
        $manager->persist($zipcode4);
        $manager->persist($zipcode5);
        $manager->persist($zipcode6);
        $manager->flush();
    }
}
