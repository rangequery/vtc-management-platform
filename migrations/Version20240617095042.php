<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240617095042 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE adresse (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(150) NOT NULL, adresse VARCHAR(150) DEFAULT NULL, code_postal VARCHAR(10) DEFAULT NULL, ville VARCHAR(150) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE service (id INT AUTO_INCREMENT NOT NULL, pick_up_from_id INT DEFAULT NULL, pick_up_to_id INT DEFAULT NULL, num_vol VARCHAR(100) DEFAULT NULL, pax VARCHAR(10) DEFAULT NULL, bagages VARCHAR(10) DEFAULT NULL, montant_ht DOUBLE PRECISION DEFAULT NULL, service_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_E19D9AD27BDC9440 (pick_up_from_id), INDEX IDX_E19D9AD24EB6A503 (pick_up_to_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE service ADD CONSTRAINT FK_E19D9AD27BDC9440 FOREIGN KEY (pick_up_from_id) REFERENCES adresse (id)');
        $this->addSql('ALTER TABLE service ADD CONSTRAINT FK_E19D9AD24EB6A503 FOREIGN KEY (pick_up_to_id) REFERENCES adresse (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE service DROP FOREIGN KEY FK_E19D9AD27BDC9440');
        $this->addSql('ALTER TABLE service DROP FOREIGN KEY FK_E19D9AD24EB6A503');
        $this->addSql('DROP TABLE adresse');
        $this->addSql('DROP TABLE service');
    }
}
