<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250722102503 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bouteille ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE bouteille ADD CONSTRAINT FK_11157C4CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_11157C4CA76ED395 ON bouteille (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bouteille DROP FOREIGN KEY FK_11157C4CA76ED395');
        $this->addSql('DROP INDEX IDX_11157C4CA76ED395 ON bouteille');
        $this->addSql('ALTER TABLE bouteille DROP user_id');
    }
}
