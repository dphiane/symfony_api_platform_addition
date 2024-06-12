<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240604131847 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cash_register_journal ADD numero_journal VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE invoice ADD cash_register_journal_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE invoice ADD CONSTRAINT FK_906517444AA641A4 FOREIGN KEY (cash_register_journal_id) REFERENCES cash_register_journal (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_906517444AA641A4 ON invoice (cash_register_journal_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE cash_register_journal DROP numero_journal');
        $this->addSql('ALTER TABLE invoice DROP CONSTRAINT FK_906517444AA641A4');
        $this->addSql('DROP INDEX IDX_906517444AA641A4');
        $this->addSql('ALTER TABLE invoice DROP cash_register_journal_id');
    }
}
