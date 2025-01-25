<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241024143425 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE demandeur (id INT AUTO_INCREMENT NOT NULL, adresse_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, telephone VARCHAR(25) DEFAULT NULL, INDEX IDX_665DA6134DE7DC5C (adresse_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE demandeur ADD CONSTRAINT FK_665DA6134DE7DC5C FOREIGN KEY (adresse_id) REFERENCES adresse (id)');
        $this->addSql('ALTER TABLE service ADD demandeur_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE service ADD CONSTRAINT FK_E19D9AD295A6EE59 FOREIGN KEY (demandeur_id) REFERENCES demandeur (id)');
        $this->addSql('CREATE INDEX IDX_E19D9AD295A6EE59 ON service (demandeur_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE service DROP FOREIGN KEY FK_E19D9AD295A6EE59');
        $this->addSql('ALTER TABLE demandeur DROP FOREIGN KEY FK_665DA6134DE7DC5C');
        $this->addSql('DROP TABLE demandeur');
        $this->addSql('DROP INDEX IDX_E19D9AD295A6EE59 ON service');
        $this->addSql('ALTER TABLE service DROP demandeur_id');
    }
}
