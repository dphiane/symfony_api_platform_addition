<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240519082742 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE invoice DROP "time"');
        $this->addSql('ALTER TABLE invoice ALTER tva TYPE INT');
        $this->addSql('ALTER TABLE payment ADD bank_cheque DOUBLE PRECISION NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE invoice ADD "time" TIME(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE invoice ALTER tva TYPE DOUBLE PRECISION');
        $this->addSql('ALTER TABLE payment DROP bank_cheque');
    }
}
