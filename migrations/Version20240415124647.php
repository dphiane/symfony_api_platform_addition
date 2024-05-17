<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240415124647 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE invoice_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE invoice (id INT NOT NULL, date DATE NOT NULL, time TIME(0) WITHOUT TIME ZONE NOT NULL, tva DOUBLE PRECISION NOT NULL, total DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE invoice_product (invoice_id INT NOT NULL, product_id INT NOT NULL, PRIMARY KEY(invoice_id, product_id))');
        $this->addSql('CREATE INDEX IDX_2193327E2989F1FD ON invoice_product (invoice_id)');
        $this->addSql('CREATE INDEX IDX_2193327E4584665A ON invoice_product (product_id)');
        $this->addSql('ALTER TABLE invoice_product ADD CONSTRAINT FK_2193327E2989F1FD FOREIGN KEY (invoice_id) REFERENCES invoice (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE invoice_product ADD CONSTRAINT FK_2193327E4584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE payment ADD invoice_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840D2989F1FD FOREIGN KEY (invoice_id) REFERENCES invoice (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_6D28840D2989F1FD ON payment (invoice_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE payment DROP CONSTRAINT FK_6D28840D2989F1FD');
        $this->addSql('DROP SEQUENCE invoice_id_seq CASCADE');
        $this->addSql('ALTER TABLE invoice_product DROP CONSTRAINT FK_2193327E2989F1FD');
        $this->addSql('ALTER TABLE invoice_product DROP CONSTRAINT FK_2193327E4584665A');
        $this->addSql('DROP TABLE invoice');
        $this->addSql('DROP TABLE invoice_product');
        $this->addSql('DROP INDEX IDX_6D28840D2989F1FD');
        $this->addSql('ALTER TABLE payment DROP invoice_id');
    }
}
