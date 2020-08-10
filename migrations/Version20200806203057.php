<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200806203057 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE booking ADD id_user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDE79F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_E00CEDDE79F37AE5 ON booking (id_user_id)');
        $this->addSql('ALTER TABLE user ADD telephone VARCHAR(16) NOT NULL, ADD nom VARCHAR(100) NOT NULL, ADD prenom VARCHAR(100) DEFAULT NULL, ADD adresse VARCHAR(255) DEFAULT NULL, ADD code_postal INT DEFAULT NULL, ADD ville VARCHAR(100) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE booking DROP FOREIGN KEY FK_E00CEDDE79F37AE5');
        $this->addSql('DROP INDEX IDX_E00CEDDE79F37AE5 ON booking');
        $this->addSql('ALTER TABLE booking DROP id_user_id');
        $this->addSql('ALTER TABLE user DROP telephone, DROP nom, DROP prenom, DROP adresse, DROP code_postal, DROP ville');
    }
}
