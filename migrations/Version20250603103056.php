<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250603103056 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE matches (id INT AUTO_INCREMENT NOT NULL, poule_id INT NOT NULL, club1_id INT NOT NULL, club2_id INT NOT NULL, date DATETIME NOT NULL, INDEX IDX_62615BA26596FD8 (poule_id), INDEX IDX_62615BA1EDA6519 (club1_id), INDEX IDX_62615BAC6FCAF7 (club2_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE matches ADD CONSTRAINT FK_62615BA26596FD8 FOREIGN KEY (poule_id) REFERENCES poule (id)');
        $this->addSql('ALTER TABLE matches ADD CONSTRAINT FK_62615BA1EDA6519 FOREIGN KEY (club1_id) REFERENCES club (id)');
        $this->addSql('ALTER TABLE matches ADD CONSTRAINT FK_62615BAC6FCAF7 FOREIGN KEY (club2_id) REFERENCES club (id)');
        $this->addSql('ALTER TABLE `match` DROP FOREIGN KEY FK_7A5BC5051EDA6519');
        $this->addSql('ALTER TABLE `match` DROP FOREIGN KEY FK_7A5BC50526596FD8');
        $this->addSql('ALTER TABLE `match` DROP FOREIGN KEY FK_7A5BC505C6FCAF7');
        $this->addSql('DROP TABLE `match`');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `match` (id INT AUTO_INCREMENT NOT NULL, poule_id INT NOT NULL, club1_id INT NOT NULL, club2_id INT NOT NULL, date DATETIME NOT NULL, INDEX IDX_7A5BC5051EDA6519 (club1_id), INDEX IDX_7A5BC50526596FD8 (poule_id), INDEX IDX_7A5BC505C6FCAF7 (club2_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE `match` ADD CONSTRAINT FK_7A5BC5051EDA6519 FOREIGN KEY (club1_id) REFERENCES club (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE `match` ADD CONSTRAINT FK_7A5BC50526596FD8 FOREIGN KEY (poule_id) REFERENCES poule (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE `match` ADD CONSTRAINT FK_7A5BC505C6FCAF7 FOREIGN KEY (club2_id) REFERENCES club (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE matches DROP FOREIGN KEY FK_62615BA26596FD8');
        $this->addSql('ALTER TABLE matches DROP FOREIGN KEY FK_62615BA1EDA6519');
        $this->addSql('ALTER TABLE matches DROP FOREIGN KEY FK_62615BAC6FCAF7');
        $this->addSql('DROP TABLE matches');
    }
}
