<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class VersionDropClassementPouleTable extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Suppression de la table classement_poule';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('DROP TABLE IF EXISTS classement_poule');
    }

    public function down(Schema $schema): void
    {
        // Cette migration est irréversible
        $this->abortIf(true, 'La suppression de la table classement_poule est irréversible.');
    }
}
