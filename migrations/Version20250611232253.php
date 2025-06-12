<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250611232253 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE classement_poule (id INT AUTO_INCREMENT NOT NULL, club_id INT NOT NULL, poule_id INT NOT NULL, points INT NOT NULL, buts_pour INT NOT NULL, butscontre INT NOT NULL, goalaverage INT NOT NULL, matchsjouer INT NOT NULL, gagnes INT NOT NULL, nuls INT NOT NULL, perdus INT NOT NULL, rang INT NOT NULL, INDEX IDX_B10C22A961190A32 (club_id), INDEX IDX_B10C22A926596FD8 (poule_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE classement_poule ADD CONSTRAINT FK_B10C22A961190A32 FOREIGN KEY (club_id) REFERENCES club (id)');
        $this->addSql('ALTER TABLE classement_poule ADD CONSTRAINT FK_B10C22A926596FD8 FOREIGN KEY (poule_id) REFERENCES poule (id)');
        $this->addSql('ALTER TABLE matches ADD score_club1 INT NOT NULL, ADD score_club2 INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE classement_poule DROP FOREIGN KEY FK_B10C22A961190A32');
        $this->addSql('ALTER TABLE classement_poule DROP FOREIGN KEY FK_B10C22A926596FD8');
        $this->addSql('DROP TABLE classement_poule');
        $this->addSql('ALTER TABLE matches DROP score_club1, DROP score_club2');
    }
}
