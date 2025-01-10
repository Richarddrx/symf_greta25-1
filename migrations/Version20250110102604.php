<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250110102604 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE conference_categorie (conference_id INT NOT NULL, categorie_id INT NOT NULL, INDEX IDX_5D187AC604B8382 (conference_id), INDEX IDX_5D187ACBCF5E72D (categorie_id), PRIMARY KEY(conference_id, categorie_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE conference_categorie ADD CONSTRAINT FK_5D187AC604B8382 FOREIGN KEY (conference_id) REFERENCES conference (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE conference_categorie ADD CONSTRAINT FK_5D187ACBCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE conference_categorie DROP FOREIGN KEY FK_5D187AC604B8382');
        $this->addSql('ALTER TABLE conference_categorie DROP FOREIGN KEY FK_5D187ACBCF5E72D');
        $this->addSql('DROP TABLE conference_categorie');
    }
}