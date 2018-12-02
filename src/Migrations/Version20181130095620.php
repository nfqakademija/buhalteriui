<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181130095620 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE document (document_id INT AUTO_INCREMENT NOT NULL, template_id INT DEFAULT 0, original_file VARCHAR(255) NOT NULL, scan_parameter LONGBLOB DEFAULT NULL, scan_status VARCHAR(255) DEFAULT \'pending\', invoice_date DATE DEFAULT NULL, invoice_series VARCHAR(10) DEFAULT NULL, invoice_number VARCHAR(20) DEFAULT NULL, invoice_buyer_name VARCHAR(255) DEFAULT NULL, invoice_buyer_address VARCHAR(255) DEFAULT NULL, invoice_buyer_code VARCHAR(20) DEFAULT NULL, invoice_buyer_vat_code VARCHAR(20) DEFAULT NULL, invoice_total NUMERIC(10, 2) DEFAULT NULL, creat_time DATETIME NOT NULL, PRIMARY KEY(document_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE document');
    }
}
