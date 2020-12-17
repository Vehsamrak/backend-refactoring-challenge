<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20201217153715 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE job change service_id category_id int not null');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE job change category_id service_id int not null');
    }
}
