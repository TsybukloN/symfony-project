<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241230125829 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE project (id SERIAL NOT NULL, uploaded_by INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2FB3D0EEE3E73126 ON project (uploaded_by)');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EEE3E73126 FOREIGN KEY (uploaded_by) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE firmwares DROP CONSTRAINT fk_87d22575e3e73126');
        $this->addSql('DROP INDEX idx_87d22575e3e73126');
        $this->addSql('ALTER TABLE firmwares DROP name');
        $this->addSql('ALTER TABLE firmwares RENAME COLUMN uploaded_by TO firmware_id');
        $this->addSql('ALTER TABLE firmwares ADD CONSTRAINT FK_87D22575972206F2 FOREIGN KEY (firmware_id) REFERENCES firmwares (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_87D22575972206F2 ON firmwares (firmware_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE project DROP CONSTRAINT FK_2FB3D0EEE3E73126');
        $this->addSql('DROP TABLE project');
        $this->addSql('ALTER TABLE firmwares DROP CONSTRAINT FK_87D22575972206F2');
        $this->addSql('DROP INDEX IDX_87D22575972206F2');
        $this->addSql('ALTER TABLE firmwares ADD name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE firmwares RENAME COLUMN firmware_id TO uploaded_by');
        $this->addSql('ALTER TABLE firmwares ADD CONSTRAINT fk_87d22575e3e73126 FOREIGN KEY (uploaded_by) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_87d22575e3e73126 ON firmwares (uploaded_by)');
    }
}
