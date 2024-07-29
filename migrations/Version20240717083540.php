<?php
//nouvelle version généré à partir des entities et repository
declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240717083540 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create a non-unique index on the user_id column of the produit table.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE INDEX IDX_29A5EC27A76ED395 ON produit(user_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX IDX_29A5EC27A76ED395');
    }
    // public function getDescription(): string
    // {
    //     return '';
    // }

    // public function up(Schema $schema): void
    // {
    //     // $this->addSql('ALTER TABLE user ADD is_verified TINYINT(1) DEFAULT 0');
    //     // $this->addSql('ALTER TABLE user ADD genre VARCHAR(10) NOT NULL');
    //     $this->addSql('ALTER TABLE produit (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, sous_categorie_id INT NOT NULL, nom VARCHAR(255) NOT NULL, nom_es VARCHAR(255) DEFAULT NULL, description VARCHAR(255) NOT NULL, pays VARCHAR(30) DEFAULT NULL, marque VARCHAR(50) DEFAULT NULL, poids NUMERIC(10, 2) DEFAULT NULL, unite_poids VARCHAR(10) DEFAULT NULL, ingredients VARCHAR(255) DEFAULT NULL, allergenes VARCHAR(255) DEFAULT NULL, certification VARCHAR(50) DEFAULT NULL, photo1 VARCHAR(255) DEFAULT NULL, photo2 VARCHAR(255) DEFAULT NULL, prix NUMERIC(10, 2) DEFAULT NULL, visible_web TINYINT(1) NOT NULL, INDEX IDX_29A5EC27A76ED395 (user_id), INDEX IDX_29A5EC27365BF48 (sous_categorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    // }

    // public function down(Schema $schema): void
    // {
    //     // this down() migration is auto-generated, please modify it to your needs
    //      $this->addSql('DROP TABLE produit');
    // }
}
