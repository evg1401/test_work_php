<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261121172842 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add image to articles';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE articles ADD image VARCHAR(256) DEFAULT NULL');
        $this->addSql("UPDATE articles SET image = '' WHERE image IS NULL");
        $this->addSql('ALTER TABLE articles MODIFY image VARCHAR(256) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE articles DROP image');
    }
}
