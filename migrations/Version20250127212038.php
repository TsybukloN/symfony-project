<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250127212038 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE project_id_seq CASCADE');
        $this->addSql('CREATE TABLE projects (id SERIAL NOT NULL, device_id INT DEFAULT NULL, uploaded_by INT DEFAULT NULL, name VARCHAR(255) NOT NULL, firmware_ids JSON NOT NULL, description TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5C93B3A494A4C7D4 ON projects (device_id)');
        $this->addSql('CREATE INDEX IDX_5C93B3A4E3E73126 ON projects (uploaded_by)');
        $this->addSql('ALTER TABLE projects ADD CONSTRAINT FK_5C93B3A494A4C7D4 FOREIGN KEY (device_id) REFERENCES devices (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE projects ADD CONSTRAINT FK_5C93B3A4E3E73126 FOREIGN KEY (uploaded_by) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project DROP CONSTRAINT fk_2fb3d0eee3e73126');
        $this->addSql('ALTER TABLE project DROP CONSTRAINT fk_2fb3d0ee94a4c7d4');
        $this->addSql('DROP TABLE project');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE project_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE project (id SERIAL NOT NULL, uploaded_by INT DEFAULT NULL, device_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, firmware_ids JSON NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_2fb3d0ee94a4c7d4 ON project (device_id)');
        $this->addSql('CREATE INDEX idx_2fb3d0eee3e73126 ON project (uploaded_by)');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT fk_2fb3d0eee3e73126 FOREIGN KEY (uploaded_by) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT fk_2fb3d0ee94a4c7d4 FOREIGN KEY (device_id) REFERENCES devices (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE projects DROP CONSTRAINT FK_5C93B3A494A4C7D4');
        $this->addSql('ALTER TABLE projects DROP CONSTRAINT FK_5C93B3A4E3E73126');
        $this->addSql('DROP TABLE projects');
    }
}
