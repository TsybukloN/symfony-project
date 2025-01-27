<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250127204311 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE firmwares DROP CONSTRAINT fk_87d22575972206f2');
        $this->addSql('DROP INDEX idx_87d22575972206f2');
        $this->addSql('ALTER TABLE firmwares ADD firmware_file_id INT NOT NULL');
        $this->addSql('ALTER TABLE firmwares ADD media_file_ids JSON NOT NULL');
        $this->addSql('ALTER TABLE firmwares DROP firmware_id');
        $this->addSql('ALTER TABLE firmwares DROP file_path');
        $this->addSql('ALTER TABLE firmwares ALTER version TYPE VARCHAR(10)');
        $this->addSql('ALTER TABLE firmwares ALTER uploaded_at SET NOT NULL');
        $this->addSql('ALTER TABLE project ADD firmware_ids JSON NOT NULL');
        $this->addSql('ALTER TABLE uploads DROP CONSTRAINT fk_96117f1894a4c7d4');
        $this->addSql('DROP INDEX idx_96117f1894a4c7d4');
        $this->addSql('ALTER TABLE uploads DROP device_id');
        $this->addSql('ALTER TABLE users ALTER email TYPE VARCHAR(255)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE users ALTER email TYPE VARCHAR(180)');
        $this->addSql('ALTER TABLE firmwares ADD firmware_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE firmwares ADD file_path VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE firmwares DROP firmware_file_id');
        $this->addSql('ALTER TABLE firmwares DROP media_file_ids');
        $this->addSql('ALTER TABLE firmwares ALTER version TYPE VARCHAR(50)');
        $this->addSql('ALTER TABLE firmwares ALTER uploaded_at DROP NOT NULL');
        $this->addSql('ALTER TABLE firmwares ADD CONSTRAINT fk_87d22575972206f2 FOREIGN KEY (firmware_id) REFERENCES firmwares (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_87d22575972206f2 ON firmwares (firmware_id)');
        $this->addSql('ALTER TABLE uploads ADD device_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE uploads ADD CONSTRAINT fk_96117f1894a4c7d4 FOREIGN KEY (device_id) REFERENCES devices (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_96117f1894a4c7d4 ON uploads (device_id)');
        $this->addSql('ALTER TABLE project DROP firmware_ids');
    }
}
