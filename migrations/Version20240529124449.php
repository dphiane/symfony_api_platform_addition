<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240529124449 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE payment ADD amount DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE payment ADD payment_method VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE payment DROP cash');
        $this->addSql('ALTER TABLE payment DROP credit_card');
        $this->addSql('ALTER TABLE payment DROP restaurant_voucher');
        $this->addSql('ALTER TABLE payment DROP bank_cheque');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE payment ADD cash DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE payment ADD credit_card DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE payment ADD restaurant_voucher DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE payment ADD bank_cheque DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE payment DROP amount');
        $this->addSql('ALTER TABLE payment DROP payment_method');
    }
}
