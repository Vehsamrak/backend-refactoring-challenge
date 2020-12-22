<?php

declare(strict_types=1);

namespace AppBundle\Tests\Controller\EntityFixtures;

use AppBundle\Entity\JobCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class JobCategoryFixtures extends Fixture
{
    public const EXISTING_JOB_CATEGORY_ID_1 = 804040;
    public const EXISTING_JOB_CATEGORY_ID_2 = 802030;
    public const UNEXISTING_JOB_CATEGORY_ID = 12345;
    public const EXISTING_JOB_CATEGORY_NAME_1 = 'Sonstige Umzugsleistungen';
    public const EXISTING_JOB_CATEGORY_NAME_2 = 'Abtransport, Entsorgung und Entrümpelung';

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager): void
    {
        $manager->persist(new JobCategory(self::EXISTING_JOB_CATEGORY_ID_1, self::EXISTING_JOB_CATEGORY_NAME_1));
        $manager->persist(new JobCategory(self::EXISTING_JOB_CATEGORY_ID_2, self::EXISTING_JOB_CATEGORY_NAME_2));
        $manager->persist(new JobCategory(411070, 'Fensterreinigung'));
        $manager->persist(new JobCategory(402020, 'Holzdielen schleifen'));
        $manager->persist(new JobCategory(108140, 'Kellersanierung'));
        $manager->flush();
    }
}
