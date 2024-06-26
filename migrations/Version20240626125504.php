<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240626125504 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE invoice DROP CONSTRAINT fk_906517444aa641a4');
        $this->addSql('DROP SEQUENCE cash_register_journal_id_seq CASCADE');
        $this->addSql('DROP TABLE cash_register_journal');
        $this->addSql('DROP INDEX idx_906517444aa641a4');
        $this->addSql('ALTER TABLE invoice DROP cash_register_journal_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE cash_register_journal_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE cash_register_journal (id INT NOT NULL, cash_fund DOUBLE PRECISION NOT NULL, total DOUBLE PRECISION DEFAULT NULL, numero_journal VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, closed_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN cash_register_journal.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN cash_register_journal.closed_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE invoice ADD cash_register_journal_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE invoice ADD CONSTRAINT fk_906517444aa641a4 FOREIGN KEY (cash_register_journal_id) REFERENCES cash_register_journal (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_906517444aa641a4 ON invoice (cash_register_journal_id)');
    }
}
