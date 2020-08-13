<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200813162634 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE energie (id INT AUTO_INCREMENT NOT NULL, nom_energie VARCHAR(100) NOT NULL, tarif_energie DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE marque (id INT AUTO_INCREMENT NOT NULL, nom_marque VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE booking ADD marque_id INT DEFAULT NULL, ADD energie_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDE4827B9B2 FOREIGN KEY (marque_id) REFERENCES marque (id)');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDEB732A364 FOREIGN KEY (energie_id) REFERENCES energie (id)');
        $this->addSql('CREATE INDEX IDX_E00CEDDE4827B9B2 ON booking (marque_id)');
        $this->addSql('CREATE INDEX IDX_E00CEDDEB732A364 ON booking (energie_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE booking DROP FOREIGN KEY FK_E00CEDDEB732A364');
        $this->addSql('ALTER TABLE booking DROP FOREIGN KEY FK_E00CEDDE4827B9B2');
        $this->addSql('DROP TABLE energie');
        $this->addSql('DROP TABLE marque');
        $this->addSql('DROP INDEX IDX_E00CEDDE4827B9B2 ON booking');
        $this->addSql('DROP INDEX IDX_E00CEDDEB732A364 ON booking');
        $this->addSql('ALTER TABLE booking DROP marque_id, DROP energie_id');
    }
}
