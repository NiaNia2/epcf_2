<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250722102834 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bouteille ADD region_id INT DEFAULT NULL, ADD pays_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE bouteille ADD CONSTRAINT FK_11157C4C98260155 FOREIGN KEY (region_id) REFERENCES regions (id)');
        $this->addSql('ALTER TABLE bouteille ADD CONSTRAINT FK_11157C4CA6E44244 FOREIGN KEY (pays_id) REFERENCES pays (id)');
        $this->addSql('CREATE INDEX IDX_11157C4C98260155 ON bouteille (region_id)');
        $this->addSql('CREATE INDEX IDX_11157C4CA6E44244 ON bouteille (pays_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bouteille DROP FOREIGN KEY FK_11157C4C98260155');
        $this->addSql('ALTER TABLE bouteille DROP FOREIGN KEY FK_11157C4CA6E44244');
        $this->addSql('DROP INDEX IDX_11157C4C98260155 ON bouteille');
        $this->addSql('DROP INDEX IDX_11157C4CA6E44244 ON bouteille');
        $this->addSql('ALTER TABLE bouteille DROP region_id, DROP pays_id');
    }
}
