<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250206183521 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE firmware_file_storage ADD mime_type VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE uploads DROP CONSTRAINT fk_96117f18972206f2');
        $this->addSql('ALTER TABLE uploads DROP CONSTRAINT fk_96117f18a76ed395');
        $this->addSql('DROP INDEX idx_96117f18a76ed395');
        $this->addSql('DROP INDEX idx_96117f18972206f2');
        $this->addSql('ALTER TABLE uploads ALTER firmware_id SET NOT NULL');
        $this->addSql('ALTER TABLE uploads ALTER user_id SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE uploads ALTER firmware_id DROP NOT NULL');
        $this->addSql('ALTER TABLE uploads ALTER user_id DROP NOT NULL');
        $this->addSql('ALTER TABLE uploads ADD CONSTRAINT fk_96117f18972206f2 FOREIGN KEY (firmware_id) REFERENCES firmwares (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE uploads ADD CONSTRAINT fk_96117f18a76ed395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_96117f18a76ed395 ON uploads (user_id)');
        $this->addSql('CREATE INDEX idx_96117f18972206f2 ON uploads (firmware_id)');
        $this->addSql('ALTER TABLE firmware_file_storage DROP mime_type');
    }
}
