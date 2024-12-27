<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241226182459 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE devices (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, model VARCHAR(100) NOT NULL, description TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE firmwares (id SERIAL NOT NULL, uploaded_by INT DEFAULT NULL, name VARCHAR(255) NOT NULL, file_path VARCHAR(255) NOT NULL, version VARCHAR(50) NOT NULL, uploaded_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_87D22575E3E73126 ON firmwares (uploaded_by)');
        $this->addSql('COMMENT ON COLUMN firmwares.uploaded_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE uploads (id SERIAL NOT NULL, firmware_id INT DEFAULT NULL, device_id INT DEFAULT NULL, user_id INT DEFAULT NULL, uploaded_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_96117F18972206F2 ON uploads (firmware_id)');
        $this->addSql('CREATE INDEX IDX_96117F1894A4C7D4 ON uploads (device_id)');
        $this->addSql('CREATE INDEX IDX_96117F18A76ED395 ON uploads (user_id)');
        $this->addSql('COMMENT ON COLUMN uploads.uploaded_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE users (id SERIAL NOT NULL, username VARCHAR(255) NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9E7927C74 ON users (email)');
        $this->addSql('ALTER TABLE firmwares ADD CONSTRAINT FK_87D22575E3E73126 FOREIGN KEY (uploaded_by) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE uploads ADD CONSTRAINT FK_96117F18972206F2 FOREIGN KEY (firmware_id) REFERENCES firmwares (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE uploads ADD CONSTRAINT FK_96117F1894A4C7D4 FOREIGN KEY (device_id) REFERENCES devices (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE uploads ADD CONSTRAINT FK_96117F18A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE firmwares DROP CONSTRAINT FK_87D22575E3E73126');
        $this->addSql('ALTER TABLE uploads DROP CONSTRAINT FK_96117F18972206F2');
        $this->addSql('ALTER TABLE uploads DROP CONSTRAINT FK_96117F1894A4C7D4');
        $this->addSql('ALTER TABLE uploads DROP CONSTRAINT FK_96117F18A76ED395');
        $this->addSql('DROP TABLE devices');
        $this->addSql('DROP TABLE firmwares');
        $this->addSql('DROP TABLE uploads');
        $this->addSql('DROP TABLE users');
    }
}
