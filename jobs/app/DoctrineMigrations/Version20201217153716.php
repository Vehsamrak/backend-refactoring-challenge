<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20201217153716 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        $this->addSql('
            ALTER TABLE job
            DROP FOREIGN KEY job_ibfk_3,
            DROP FOREIGN KEY job_ibfk_4,
            DROP FOREIGN KEY job_ibfk_1,
            ADD CONSTRAINT fk__job__category_id FOREIGN KEY (category_id) REFERENCES job_category (id),
            DROP FOREIGN KEY job_ibfk_2,
            ADD CONSTRAINT fk__job__zipcode_id FOREIGN KEY (zipcode_id) REFERENCES zipcode (id)
        ');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('
            ALTER TABLE job
            ADD CONSTRAINT job_ibfk_3 FOREIGN KEY (category_id) REFERENCES service (id) ON UPDATE CASCADE ON DELETE CASCADE,
            ADD CONSTRAINT job_ibfk_4 FOREIGN KEY (zipcode_id) REFERENCES zipcode (id) ON UPDATE CASCADE ON DELETE CASCADE,
            DROP FOREIGN KEY fk__job__category_id,
            ADD CONSTRAINT job_ibfk_1 FOREIGN KEY (category_id) REFERENCES job_category (id),
            DROP FOREIGN KEY fk__job__zipcode_id,
            ADD CONSTRAINT job_ibfk_2 FOREIGN KEY (zipcode_id) REFERENCES zipcode (id)
        ');
    }
}
