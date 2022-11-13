<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221111151042 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, sensor_id INT NOT NULL, time_stamp DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', type ENUM(\'Traffic Light State\', \'Error\'), error_content ENUM(\'Internal Sensor Error\', \'Traffic Light Error\', \'Bandwidth Error\'), state_content ENUM(\'Red\', \'Yellow\', \'Green\', \'Unknown State\'), INDEX IDX_B6BD307FA247991F (sensor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sensor (id INT NOT NULL, last_seen_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', last_error ENUM(\'Internal Sensor Error\', \'Traffic Light Error\', \'Bandwidth Error\'), last_state ENUM(\'Red\', \'Yellow\', \'Green\', \'Unknown State\'), latitude NUMERIC(10, 8) DEFAULT NULL, longitude NUMERIC(11, 8) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FA247991F FOREIGN KEY (sensor_id) REFERENCES sensor (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FA247991F');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE sensor');
    }
}
