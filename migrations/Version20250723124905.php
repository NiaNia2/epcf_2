<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250723124905 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bouteille DROP FOREIGN KEY FK_11157C4CA6E44244');
        $this->addSql('DROP INDEX IDX_11157C4CA6E44244 ON bouteille');
        $this->addSql('ALTER TABLE bouteille ADD updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', DROP pays_id');
        $this->addSql('ALTER TABLE regions ADD pays_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE regions ADD CONSTRAINT FK_A26779F3A6E44244 FOREIGN KEY (pays_id) REFERENCES pays (id)');
        $this->addSql('CREATE INDEX IDX_A26779F3A6E44244 ON regions (pays_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bouteille ADD pays_id INT DEFAULT NULL, DROP updated_at');
        $this->addSql('ALTER TABLE bouteille ADD CONSTRAINT FK_11157C4CA6E44244 FOREIGN KEY (pays_id) REFERENCES pays (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_11157C4CA6E44244 ON bouteille (pays_id)');
        $this->addSql('ALTER TABLE regions DROP FOREIGN KEY FK_A26779F3A6E44244');
        $this->addSql('DROP INDEX IDX_A26779F3A6E44244 ON regions');
        $this->addSql('ALTER TABLE regions DROP pays_id');
    }
}
