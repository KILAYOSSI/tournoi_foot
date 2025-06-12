<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class VersionFixDropClassementPouleTable extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Supprime proprement la table classement_poule en supprimant d\'abord les colonnes conflictuelles';
    }

    public function up(Schema $schema): void
    {
        // Supprimer les colonnes conflictuelles si elles existent
        $this->addSql('ALTER TABLE classement_poule DROP COLUMN IF EXISTS cartons_jaunes');
        $this->addSql('ALTER TABLE classement_poule DROP COLUMN IF EXISTS cartons_rouges');
        $this->addSql('ALTER TABLE classement_poule DROP COLUMN IF EXISTS gagnes');
        $this->addSql('ALTER TABLE classement_poule DROP COLUMN IF EXISTS nuls');
        $this->addSql('ALTER TABLE classement_poule DROP COLUMN IF EXISTS perdus');

        // Supprimer la table
        $this->addSql('DROP TABLE IF EXISTS classement_poule');
    }

    public function down(Schema $schema): void
    {
        // Recréer la table classement_poule (simplifiée)
        $this->addSql('CREATE TABLE classement_poule (
            id INT AUTO_INCREMENT NOT NULL,
            club_id INT DEFAULT NULL,
            poule_id INT NOT NULL,
            points INT NOT NULL,
            buts_pour INT NOT NULL,
            butscontre INT NOT NULL,
            goalaverage INT NOT NULL,
            matchsjouer INT NOT NULL,
            rang INT NOT NULL,
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }
}
