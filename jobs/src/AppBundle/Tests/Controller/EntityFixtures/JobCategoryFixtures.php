<?php

declare(strict_types=1);

namespace AppBundle\Tests\Controller\EntityFixtures;

use AppBundle\Entity\JobCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class JobCategoryFixtures extends Fixture
{
    public const EXISTING_JOB_CATEGORY_ID = 804040;
    public const UNEXISTING_JOB_CATEGORY_ID = 12345;

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager): void
    {
        $manager->persist(new JobCategory(self::EXISTING_JOB_CATEGORY_ID, 'Sonstige Umzugsleistungen'));
        $manager->persist(new JobCategory(802030, 'Abtransport, Entsorgung und Entrümpelung'));
        $manager->persist(new JobCategory(411070, 'Fensterreinigung'));
        $manager->persist(new JobCategory(402020, 'Holzdielen schleifen'));
        $manager->persist(new JobCategory(108140, 'Kellersanierung'));
        $manager->flush();
    }
}
