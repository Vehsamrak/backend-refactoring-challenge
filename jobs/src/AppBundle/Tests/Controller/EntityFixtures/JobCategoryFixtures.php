<?php

declare(strict_types=1);

namespace AppBundle\Tests\Controller\EntityFixtures;

use AppBundle\Entity\JobCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class JobCategoryFixtures extends Fixture
{
    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager): void
    {
        $manager->persist(new JobCategory(804040, 'Sonstige Umzugsleistungen'));
        $manager->persist(new JobCategory(802030, 'Abtransport, Entsorgung und Entrümpelung'));
        $manager->persist(new JobCategory(411070, 'Fensterreinigung'));
        $manager->persist(new JobCategory(402020, 'Holzdielen schleifen'));
        $manager->persist(new JobCategory(108140, 'Kellersanierung'));
        $manager->flush();
    }
}
