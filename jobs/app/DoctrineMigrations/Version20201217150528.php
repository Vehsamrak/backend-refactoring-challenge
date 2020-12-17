<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20201217150528 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        $this->addSql('RENAME TABLE `service` TO `job_category`');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('RENAME TABLE `job_category` TO `service`');
    }
}
