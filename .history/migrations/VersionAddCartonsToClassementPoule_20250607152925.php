<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class VersionAddCartonsToClassementPoule extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Ajout des colonnes cartons_jaunes et cartons_rouges dans la table classement_poule';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE classement_poule ADD cartons_jaunes INT NOT NULL DEFAULT 0');
        $this->addSql('ALTER TABLE classement_poule ADD cartons_rouges INT NOT NULL DEFAULT 0');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE classement_poule DROP cartons_jaunes');
        $this->addSql('ALTER TABLE classement_poule DROP cartons_rouges');
    }
}
