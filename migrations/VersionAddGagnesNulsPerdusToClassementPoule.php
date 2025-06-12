<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class VersionAddGagnesNulsPerdusToClassementPoule extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Ajout des colonnes gagnes, nuls, perdus dans la table classement_poule';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE classement_poule ADD gagnes INT NOT NULL DEFAULT 0');
        $this->addSql('ALTER TABLE classement_poule ADD nuls INT NOT NULL DEFAULT 0');
        $this->addSql('ALTER TABLE classement_poule ADD perdus INT NOT NULL DEFAULT 0');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE classement_poule DROP gagnes');
        $this->addSql('ALTER TABLE classement_poule DROP nuls');
        $this->addSql('ALTER TABLE classement_poule DROP perdus');
    }
}
