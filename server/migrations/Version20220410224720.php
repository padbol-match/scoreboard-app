<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220410224720 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pdbscrb_device ADD tenant_id INT DEFAULT NULL, DROP tenant');
        $this->addSql('ALTER TABLE pdbscrb_device ADD CONSTRAINT FK_4D0CC1BA9033212A FOREIGN KEY (tenant_id) REFERENCES pdbscrb_tenant (id)');
        $this->addSql('CREATE INDEX IDX_4D0CC1BA9033212A ON pdbscrb_device (tenant_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pdbscrb_device DROP FOREIGN KEY FK_4D0CC1BA9033212A');
        $this->addSql('DROP INDEX IDX_4D0CC1BA9033212A ON pdbscrb_device');
        $this->addSql('ALTER TABLE pdbscrb_device ADD tenant INT NOT NULL, DROP tenant_id, CHANGE device_id device_id VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE token token VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE team1_button_code team1_button_code VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE team2_button_code team2_button_code VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE pdbscrb_tenant CHANGE token token VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
