<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220411002818 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pdbscrb_device CHANGE field field INT DEFAULT NULL, CHANGE team1_button_code team1_button_code VARCHAR(255) DEFAULT NULL, CHANGE team2_button_code team2_button_code VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pdbscrb_device CHANGE device_id device_id VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE token token VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE field field INT NOT NULL, CHANGE team1_button_code team1_button_code VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE team2_button_code team2_button_code VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE pdbscrb_tenant CHANGE token token VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
