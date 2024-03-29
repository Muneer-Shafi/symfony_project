<?php

declare(strict_types=1);

/*
 * This file is part of Muneer's learning project.
 *
 * (c) Muneer shafi <mcamuneershafi@gmail.com>.
 */

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230520172305 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
                    CREATE TABLE `sessions` (
                        `sess_id` VARBINARY(128) NOT NULL PRIMARY KEY,
                        `sess_data` BLOB NOT NULL,
                        `sess_lifetime` INTEGER UNSIGNED NOT NULL,
                        `sess_time` INTEGER UNSIGNED NOT NULL,
                        INDEX `sessions_sess_lifetime_idx` (`sess_lifetime`)
                    ) COLLATE utf8mb4_bin, ENGINE = InnoDB;
                ');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
    }
}
