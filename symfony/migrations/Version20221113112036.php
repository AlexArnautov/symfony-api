<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221113112036 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            "
INSERT INTO sensors.sensor (id, last_seen_at, last_error, last_state, latitude, longitude, stats) VALUES (1, '2022-11-11 19:27:21', null, 'Red', 52.36933200, 4.88488100, '{\"last3days\": {\"red\": 3233, \"green\": 3345, \"yellow\": 3434}}');
INSERT INTO sensors.sensor (id, last_seen_at, last_error, last_state, latitude, longitude, stats) VALUES (2, '2022-11-11 19:27:21', null, 'Green', 52.37796600, 4.89707000, '{\"last3days\": {\"red\": 433, \"green\": 345, \"yellow\": 344}}');
"
        );
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(
            "
INSERT INTO sensors.message (id, sensor_id, time_stamp, type, error_content, state_content) VALUES (1, 1, '2022-11-12 00:41:02', 'Error', 'Internal Sensor Error', null);
INSERT INTO sensors.message (id, sensor_id, time_stamp, type, error_content, state_content) VALUES (2, 1, '2022-10-12 00:51:22', 'Error', 'Internal Sensor Error', null);
"
        );
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("TRUNCATE TABLE message");
        $this->addSql("TRUNCATE TABLE sensor");
    }
}
