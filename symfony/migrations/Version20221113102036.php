<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221113102036 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE message CHANGE type type ENUM(\'Traffic Light State\', \'Error\')');
        $this->addSql('ALTER TABLE sensor ADD stats JSON NOT NULL, CHANGE latitude latitude NUMERIC(10, 8) NOT NULL, CHANGE longitude longitude NUMERIC(11, 8) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE message CHANGE type type VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE sensor DROP stats, CHANGE latitude latitude NUMERIC(10, 8) DEFAULT NULL, CHANGE longitude longitude NUMERIC(11, 8) DEFAULT NULL');
    }
}
