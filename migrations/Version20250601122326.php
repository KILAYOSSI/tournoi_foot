<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250601122326 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE classement_poule (id INT AUTO_INCREMENT NOT NULL, club_id INT DEFAULT NULL, points INT NOT NULL, buts_pour INT NOT NULL, butscontre INT NOT NULL, goalaverage INT NOT NULL, matchsjouer INT NOT NULL, INDEX IDX_B10C22A961190A32 (club_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE club (id INT AUTO_INCREMENT NOT NULL, poule_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, logo VARCHAR(255) NOT NULL, INDEX IDX_B8EE387226596FD8 (poule_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE joueur (id INT AUTO_INCREMENT NOT NULL, club_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, poste VARCHAR(255) NOT NULL, INDEX IDX_FD71A9C561190A32 (club_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `match` (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE poule (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rencontre (id INT AUTO_INCREMENT NOT NULL, club1_id INT DEFAULT NULL, club2_id INT DEFAULT NULL, date_heure DATETIME NOT NULL, scoreclub1 INT NOT NULL, scoreclub2 INT NOT NULL, joue TINYINT(1) NOT NULL, INDEX IDX_460C35ED1EDA6519 (club1_id), INDEX IDX_460C35EDC6FCAF7 (club2_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE statistiquematch (id INT AUTO_INCREMENT NOT NULL, rencontre_id INT DEFAULT NULL, joueur_id INT DEFAULT NULL, buts INT NOT NULL, passes INT NOT NULL, cleansheet TINYINT(1) NOT NULL, cartonsjaunes INT NOT NULL, cartonsrouges INT NOT NULL, INDEX IDX_1A1939396CFC0818 (rencontre_id), INDEX IDX_1A193939A9E2D76C (joueur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vote (id INT AUTO_INCREMENT NOT NULL, rencontre_id INT DEFAULT NULL, joueur_id INT DEFAULT NULL, club_id INT DEFAULT NULL, type VARCHAR(255) NOT NULL, montant DOUBLE PRECISION NOT NULL, auteur_ip VARCHAR(255) NOT NULL, date DATETIME NOT NULL, INDEX IDX_5A1085646CFC0818 (rencontre_id), INDEX IDX_5A108564A9E2D76C (joueur_id), INDEX IDX_5A10856461190A32 (club_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE classement_poule ADD CONSTRAINT FK_B10C22A961190A32 FOREIGN KEY (club_id) REFERENCES club (id)');
        $this->addSql('ALTER TABLE club ADD CONSTRAINT FK_B8EE387226596FD8 FOREIGN KEY (poule_id) REFERENCES poule (id)');
        $this->addSql('ALTER TABLE joueur ADD CONSTRAINT FK_FD71A9C561190A32 FOREIGN KEY (club_id) REFERENCES club (id)');
        $this->addSql('ALTER TABLE rencontre ADD CONSTRAINT FK_460C35ED1EDA6519 FOREIGN KEY (club1_id) REFERENCES club (id)');
        $this->addSql('ALTER TABLE rencontre ADD CONSTRAINT FK_460C35EDC6FCAF7 FOREIGN KEY (club2_id) REFERENCES club (id)');
        $this->addSql('ALTER TABLE statistiquematch ADD CONSTRAINT FK_1A1939396CFC0818 FOREIGN KEY (rencontre_id) REFERENCES rencontre (id)');
        $this->addSql('ALTER TABLE statistiquematch ADD CONSTRAINT FK_1A193939A9E2D76C FOREIGN KEY (joueur_id) REFERENCES joueur (id)');
        $this->addSql('ALTER TABLE vote ADD CONSTRAINT FK_5A1085646CFC0818 FOREIGN KEY (rencontre_id) REFERENCES rencontre (id)');
        $this->addSql('ALTER TABLE vote ADD CONSTRAINT FK_5A108564A9E2D76C FOREIGN KEY (joueur_id) REFERENCES joueur (id)');
        $this->addSql('ALTER TABLE vote ADD CONSTRAINT FK_5A10856461190A32 FOREIGN KEY (club_id) REFERENCES club (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE classement_poule DROP FOREIGN KEY FK_B10C22A961190A32');
        $this->addSql('ALTER TABLE club DROP FOREIGN KEY FK_B8EE387226596FD8');
        $this->addSql('ALTER TABLE joueur DROP FOREIGN KEY FK_FD71A9C561190A32');
        $this->addSql('ALTER TABLE rencontre DROP FOREIGN KEY FK_460C35ED1EDA6519');
        $this->addSql('ALTER TABLE rencontre DROP FOREIGN KEY FK_460C35EDC6FCAF7');
        $this->addSql('ALTER TABLE statistiquematch DROP FOREIGN KEY FK_1A1939396CFC0818');
        $this->addSql('ALTER TABLE statistiquematch DROP FOREIGN KEY FK_1A193939A9E2D76C');
        $this->addSql('ALTER TABLE vote DROP FOREIGN KEY FK_5A1085646CFC0818');
        $this->addSql('ALTER TABLE vote DROP FOREIGN KEY FK_5A108564A9E2D76C');
        $this->addSql('ALTER TABLE vote DROP FOREIGN KEY FK_5A10856461190A32');
        $this->addSql('DROP TABLE classement_poule');
        $this->addSql('DROP TABLE club');
        $this->addSql('DROP TABLE joueur');
        $this->addSql('DROP TABLE `match`');
        $this->addSql('DROP TABLE poule');
        $this->addSql('DROP TABLE rencontre');
        $this->addSql('DROP TABLE statistiquematch');
        $this->addSql('DROP TABLE vote');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
