<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240530075755 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE invoice_products_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE invoice_products (id INT NOT NULL, invoice_id INT NOT NULL, product_id INT NOT NULL, quantity INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_AC9200022989F1FD ON invoice_products (invoice_id)');
        $this->addSql('CREATE INDEX IDX_AC9200024584665A ON invoice_products (product_id)');
        $this->addSql('ALTER TABLE invoice_products ADD CONSTRAINT FK_AC9200022989F1FD FOREIGN KEY (invoice_id) REFERENCES invoice (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE invoice_products ADD CONSTRAINT FK_AC9200024584665A FOREIGN KEY (product_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE invoice_product DROP CONSTRAINT fk_2193327e2989f1fd');
        $this->addSql('ALTER TABLE invoice_product DROP CONSTRAINT fk_2193327e4584665a');
        $this->addSql('DROP TABLE invoice_product');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE invoice_products_id_seq CASCADE');
        $this->addSql('CREATE TABLE invoice_product (invoice_id INT NOT NULL, product_id INT NOT NULL, PRIMARY KEY(invoice_id, product_id))');
        $this->addSql('CREATE INDEX idx_2193327e4584665a ON invoice_product (product_id)');
        $this->addSql('CREATE INDEX idx_2193327e2989f1fd ON invoice_product (invoice_id)');
        $this->addSql('ALTER TABLE invoice_product ADD CONSTRAINT fk_2193327e2989f1fd FOREIGN KEY (invoice_id) REFERENCES invoice (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE invoice_product ADD CONSTRAINT fk_2193327e4584665a FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE invoice_products DROP CONSTRAINT FK_AC9200022989F1FD');
        $this->addSql('ALTER TABLE invoice_products DROP CONSTRAINT FK_AC9200024584665A');
        $this->addSql('DROP TABLE invoice_products');
    }
}
