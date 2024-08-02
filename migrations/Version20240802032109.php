<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240802032109 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evenement CHANGE plage_heure plage_heure VARCHAR(20) DEFAULT NULL, CHANGE prix prix NUMERIC(10, 2) DEFAULT NULL, CHANGE photo2 photo2 VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE produit DROP INDEX IDX_29A5EC27A76ED395, ADD UNIQUE INDEX UNIQ_29A5EC27A76ED395 (user_id)');
        $this->addSql('ALTER TABLE produit CHANGE nom_es nom_es VARCHAR(255) DEFAULT NULL, CHANGE pays pays VARCHAR(30) DEFAULT NULL, CHANGE marque marque VARCHAR(50) DEFAULT NULL, CHANGE poids poids NUMERIC(10, 2) DEFAULT NULL, CHANGE unite_poids unite_poids VARCHAR(10) DEFAULT NULL, CHANGE ingredients ingredients VARCHAR(255) DEFAULT NULL, CHANGE allergenes allergenes VARCHAR(255) DEFAULT NULL, CHANGE certification certification VARCHAR(50) DEFAULT NULL, CHANGE photo1 photo1 VARCHAR(255) DEFAULT NULL, CHANGE photo2 photo2 VARCHAR(255) DEFAULT NULL, CHANGE prix prix NUMERIC(10, 2) DEFAULT NULL');
        $this->addSql('ALTER TABLE reservation CHANGE telephone_participant telephone_participant VARCHAR(20) DEFAULT NULL, CHANGE mode_paiement mode_paiement VARCHAR(50) DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL, CHANGE telephone telephone VARCHAR(20) DEFAULT NULL, CHANGE adresse adresse VARCHAR(120) DEFAULT NULL, CHANGE code_postal code_postal VARCHAR(10) DEFAULT NULL, CHANGE ville ville VARCHAR(50) DEFAULT NULL, CHANGE pays pays VARCHAR(50) DEFAULT NULL');
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evenement CHANGE plage_heure plage_heure VARCHAR(20) DEFAULT \'NULL\', CHANGE prix prix NUMERIC(10, 2) DEFAULT \'NULL\', CHANGE photo2 photo2 VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT \'NULL\' COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE produit DROP INDEX UNIQ_29A5EC27A76ED395, ADD INDEX IDX_29A5EC27A76ED395 (user_id)');
        $this->addSql('ALTER TABLE produit CHANGE nom_es nom_es VARCHAR(255) DEFAULT \'NULL\', CHANGE pays pays VARCHAR(30) DEFAULT \'NULL\', CHANGE marque marque VARCHAR(50) DEFAULT \'NULL\', CHANGE poids poids NUMERIC(10, 2) DEFAULT \'NULL\', CHANGE unite_poids unite_poids VARCHAR(10) DEFAULT \'NULL\', CHANGE ingredients ingredients VARCHAR(255) DEFAULT \'NULL\', CHANGE allergenes allergenes VARCHAR(255) DEFAULT \'NULL\', CHANGE certification certification VARCHAR(50) DEFAULT \'NULL\', CHANGE photo1 photo1 VARCHAR(255) DEFAULT \'NULL\', CHANGE photo2 photo2 VARCHAR(255) DEFAULT \'NULL\', CHANGE prix prix NUMERIC(10, 2) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE reservation CHANGE telephone_participant telephone_participant VARCHAR(20) DEFAULT \'NULL\', CHANGE mode_paiement mode_paiement VARCHAR(50) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_bin`, CHANGE telephone telephone VARCHAR(20) DEFAULT \'NULL\', CHANGE adresse adresse VARCHAR(120) DEFAULT \'NULL\', CHANGE code_postal code_postal VARCHAR(10) DEFAULT \'NULL\', CHANGE ville ville VARCHAR(50) DEFAULT \'NULL\', CHANGE pays pays VARCHAR(50) DEFAULT \'NULL\'');
    }
}
