<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class VersionAddPouleIdToClassementPoule extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add poule_id column to classement_poule table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE classement_poule ADD poule_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE classement_poule ADD CONSTRAINT FK_9E3B3B3E7E3C61F9 FOREIGN KEY (poule_id) REFERENCES poule (id)');
        $this->addSql('CREATE INDEX IDX_9E3B3B3E7E3C61F9 ON classement_poule (poule_id)');
        $this->addSql('UPDATE classement_poule cp INNER JOIN club c ON cp.club_id = c.id SET cp.poule_id = c.poule_id');
        $this->addSql('ALTER TABLE classement_poule MODIFY poule_id INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE classement_poule DROP FOREIGN KEY FK_9E3B3B3E7E3C61F9');
        $this->addSql('DROP INDEX IDX_9E3B3B3E7E3C61F9 ON classement_poule');
        $this->addSql('ALTER TABLE classement_poule DROP poule_id');
    }
}
