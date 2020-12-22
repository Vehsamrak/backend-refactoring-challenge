<?php

declare(strict_types=1);

namespace AppBundle\Tests\Controller\EntityFixtures;

use AppBundle\Entity\Job;
use AppBundle\Entity\JobCategory;
use AppBundle\Entity\Zipcode;
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
        $jobCategory1 = new JobCategory(
            JobCategoryFixtures::EXISTING_JOB_CATEGORY_ID_1,
            JobCategoryFixtures::EXISTING_JOB_CATEGORY_NAME_1
        );
        $jobCategory2 = new JobCategory(
            JobCategoryFixtures::EXISTING_JOB_CATEGORY_ID_2,
            JobCategoryFixtures::EXISTING_JOB_CATEGORY_NAME_2
        );
        $manager->persist($jobCategory1);
        $manager->persist($jobCategory2);

        $zipcode1 = new Zipcode(ZipcodeFixtures::EXISTING_ZIPCODE_ID_1, ZipcodeFixtures::EXISTING_ZIPCODE_CITY_1);
        $zipcode2 = new Zipcode(ZipcodeFixtures::EXISTING_ZIPCODE_ID_2, ZipcodeFixtures::EXISTING_ZIPCODE_CITY_2);
        $manager->persist($zipcode1);
        $manager->persist($zipcode2);

        $manager->persist(new Job($jobCategory1, $zipcode1, 'first job', new DateTime('2018-11-11'), 'decription'));
        $manager->persist(new Job($jobCategory2, $zipcode2, 'second job', new DateTime('2018-11-11'), 'decription'));

        $manager->flush();
    }
}
