<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250602083125 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `match` ADD poule_id INT NOT NULL, ADD date DATETIME NOT NULL');
        $this->addSql('ALTER TABLE `match` ADD CONSTRAINT FK_7A5BC50526596FD8 FOREIGN KEY (poule_id) REFERENCES poule (id)');
        $this->addSql('CREATE INDEX IDX_7A5BC50526596FD8 ON `match` (poule_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `match` DROP FOREIGN KEY FK_7A5BC50526596FD8');
        $this->addSql('DROP INDEX IDX_7A5BC50526596FD8 ON `match`');
        $this->addSql('ALTER TABLE `match` DROP poule_id, DROP date');
    }
}
