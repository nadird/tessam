<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231231153547 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product CHANGE sku sku VARCHAR(255) DEFAULT NULL, CHANGE short_description short_description LONGTEXT DEFAULT NULL, CHANGE long_description long_description LONGTEXT DEFAULT NULL, CHANGE bar_code bar_code INT DEFAULT NULL, CHANGE weight weight DOUBLE PRECISION DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product CHANGE sku sku VARCHAR(255) NOT NULL, CHANGE short_description short_description LONGTEXT NOT NULL, CHANGE long_description long_description LONGTEXT NOT NULL, CHANGE bar_code bar_code INT NOT NULL, CHANGE weight weight DOUBLE PRECISION NOT NULL');
    }
}
