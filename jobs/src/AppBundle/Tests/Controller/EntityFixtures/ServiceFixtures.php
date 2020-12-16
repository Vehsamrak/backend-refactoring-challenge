<?php

declare(strict_types=1);

namespace AppBundle\Tests\Controller\EntityFixtures;

use AppBundle\Entity\Service;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ServiceFixtures extends Fixture
{
    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager): void
    {
        $service1 = new Service(804040, 'Sonstige Umzugsleistungen');
        $service2 = new Service(802030, 'Abtransport, Entsorgung und Entrümpelung');
        $service3 = new Service(411070, 'Fensterreinigung');
        $service4 = new Service(402020, 'Holzdielen schleifen');
        $service5 = new Service(108140, 'Kellersanierung');
        $manager->persist($service1);
        $manager->persist($service2);
        $manager->persist($service3);
        $manager->persist($service4);
        $manager->persist($service5);
        $manager->flush();
    }
}
