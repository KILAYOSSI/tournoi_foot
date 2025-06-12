<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250612063536 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE classement_poule DROP cartons_jaunes, DROP cartons_rouges');
        $this->addSql('ALTER TABLE rencontre ADD scoreclub1 INT NOT NULL, ADD scoreclub2 INT NOT NULL, DROP score_club1, DROP score_club2');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE classement_poule ADD cartons_jaunes INT NOT NULL, ADD cartons_rouges INT NOT NULL');
        $this->addSql('ALTER TABLE rencontre ADD score_club1 INT DEFAULT NULL, ADD score_club2 INT DEFAULT NULL, DROP scoreclub1, DROP scoreclub2');
    }
}
