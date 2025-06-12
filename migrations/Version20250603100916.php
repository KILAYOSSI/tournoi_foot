<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250603100916 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `match` DROP FOREIGN KEY FK_7A5BC50526596FD8');
        $this->addSql('DROP TABLE `match`');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `match` (id INT AUTO_INCREMENT NOT NULL, poule_id INT NOT NULL, INDEX IDX_7A5BC50526596FD8 (poule_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE `match` ADD CONSTRAINT FK_7A5BC50526596FD8 FOREIGN KEY (poule_id) REFERENCES poule (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
