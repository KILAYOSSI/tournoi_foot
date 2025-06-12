<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250607113636 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE classement_poule ADD poule_id INT NOT NULL, CHANGE gagnes gagnes INT NOT NULL, CHANGE nuls nuls INT NOT NULL, CHANGE perdus perdus INT NOT NULL, CHANGE rang rang INT NOT NULL');
        $this->addSql('ALTER TABLE classement_poule ADD CONSTRAINT FK_B10C22A926596FD8 FOREIGN KEY (poule_id) REFERENCES poule (id)');
        $this->addSql('CREATE INDEX IDX_B10C22A926596FD8 ON classement_poule (poule_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE classement_poule DROP FOREIGN KEY FK_B10C22A926596FD8');
        $this->addSql('DROP INDEX IDX_B10C22A926596FD8 ON classement_poule');
        $this->addSql('ALTER TABLE classement_poule DROP poule_id, CHANGE gagnes gagnes INT DEFAULT 0 NOT NULL, CHANGE nuls nuls INT DEFAULT 0 NOT NULL, CHANGE perdus perdus INT DEFAULT 0 NOT NULL, CHANGE rang rang INT DEFAULT 0 NOT NULL');
    }
}
