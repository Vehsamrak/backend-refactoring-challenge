<?php

declare(strict_types=1);

namespace AppBundle\Tests\Controller\EntityFixtures;

use AppBundle\Entity\Job;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use DateTime;

class JobFixtures extends Fixture
{
    public const UNEXISTING_JOB_ID = '';

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager): void
    {
        $job = new Job(
            JobCategoryFixtures::EXISTING_JOB_CATEGORY_ID,
            ZipcodeFixtures::EXISTING_ZIPCODE_ID,
            'title',
            'decription',
            new DateTime('2018-11-11')
        );

        $manager->persist($job);
        $manager->flush();
    }
}
