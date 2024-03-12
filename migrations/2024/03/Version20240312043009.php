<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240312043009 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE tax_system (id VARCHAR(26) NOT NULL, country_code VARCHAR(2) NOT NULL, tax_number_masks JSON NOT NULL, amount NUMERIC(10, 2) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_85977B20F026BB7C ON tax_system (country_code)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE tax_system');
    }
}
