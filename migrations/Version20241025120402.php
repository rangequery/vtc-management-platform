<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241025120402 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE service ADD CONSTRAINT FK_E19D9AD27BDC9440 FOREIGN KEY (pick_up_from_id) REFERENCES adresse (id)');
        $this->addSql('ALTER TABLE service ADD CONSTRAINT FK_E19D9AD24EB6A503 FOREIGN KEY (pick_up_to_id) REFERENCES adresse (id)');
        $this->addSql('ALTER TABLE service ADD CONSTRAINT FK_E19D9AD285C0B3BE FOREIGN KEY (chauffeur_id) REFERENCES chauffeur (id)');
        $this->addSql('ALTER TABLE service ADD CONSTRAINT FK_E19D9AD2C54C8C93 FOREIGN KEY (type_id) REFERENCES type (id)');
        $this->addSql('ALTER TABLE service ADD CONSTRAINT FK_E19D9AD28041068 FOREIGN KEY (sous_traitent_id) REFERENCES sous_traitent (id)');
        $this->addSql('ALTER TABLE service ADD CONSTRAINT FK_E19D9AD26BF700BD FOREIGN KEY (status_id) REFERENCES status (id)');
        $this->addSql('ALTER TABLE service ADD CONSTRAINT FK_E19D9AD219EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE service ADD CONSTRAINT FK_E19D9AD295A6EE59 FOREIGN KEY (demandeur_id) REFERENCES demandeur (id)');
        $this->addSql('CREATE INDEX IDX_E19D9AD295A6EE59 ON service (demandeur_id)');
        $this->addSql('ALTER TABLE user ADD nom VARCHAR(255) NOT NULL, ADD prenom VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE service DROP FOREIGN KEY FK_E19D9AD27BDC9440');
        $this->addSql('ALTER TABLE service DROP FOREIGN KEY FK_E19D9AD24EB6A503');
        $this->addSql('ALTER TABLE service DROP FOREIGN KEY FK_E19D9AD285C0B3BE');
        $this->addSql('ALTER TABLE service DROP FOREIGN KEY FK_E19D9AD2C54C8C93');
        $this->addSql('ALTER TABLE service DROP FOREIGN KEY FK_E19D9AD28041068');
        $this->addSql('ALTER TABLE service DROP FOREIGN KEY FK_E19D9AD26BF700BD');
        $this->addSql('ALTER TABLE service DROP FOREIGN KEY FK_E19D9AD219EB6921');
        $this->addSql('ALTER TABLE service DROP FOREIGN KEY FK_E19D9AD295A6EE59');
        $this->addSql('DROP INDEX IDX_E19D9AD295A6EE59 ON service');
        $this->addSql('ALTER TABLE user DROP nom, DROP prenom');
    }
}
