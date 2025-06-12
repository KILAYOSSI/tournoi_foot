<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class VersionDropCartonsJaunesColumn extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Supprime la colonne cartons_jaunes de la table classement_poule pour éviter conflit de migration';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE classement_poule DROP COLUMN IF EXISTS cartons_jaunes');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE classement_poule ADD cartons_jaunes INT NOT NULL DEFAULT 0');
    }
}
