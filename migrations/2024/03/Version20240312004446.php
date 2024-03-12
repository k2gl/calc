<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240312004446 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE coupon (id VARCHAR(26) NOT NULL, code VARCHAR(255) NOT NULL, discount_type VARCHAR(255) NOT NULL, discount_amount NUMERIC(10, 2) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_64BF3F0277153098 ON coupon (code)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE coupon');
    }
}
