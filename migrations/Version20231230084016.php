<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231230084016 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE shop (id INT AUTO_INCREMENT NOT NULL, thumb_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_AC6A4CA2C7034EA5 (thumb_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE shop ADD CONSTRAINT FK_AC6A4CA2C7034EA5 FOREIGN KEY (thumb_id) REFERENCES image (id)');
        $this->addSql('ALTER TABLE brand ADD logo_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE brand ADD CONSTRAINT FK_1C52F958F98F144A FOREIGN KEY (logo_id) REFERENCES image (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1C52F958F98F144A ON brand (logo_id)');
        $this->addSql('ALTER TABLE image ADD product_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('CREATE INDEX IDX_C53D045F4584665A ON image (product_id)');
        $this->addSql('ALTER TABLE supplier ADD logo_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE supplier ADD CONSTRAINT FK_9B2A6C7EF98F144A FOREIGN KEY (logo_id) REFERENCES image (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9B2A6C7EF98F144A ON supplier (logo_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shop DROP FOREIGN KEY FK_AC6A4CA2C7034EA5');
        $this->addSql('DROP TABLE shop');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045F4584665A');
        $this->addSql('DROP INDEX IDX_C53D045F4584665A ON image');
        $this->addSql('ALTER TABLE image DROP product_id');
        $this->addSql('ALTER TABLE brand DROP FOREIGN KEY FK_1C52F958F98F144A');
        $this->addSql('DROP INDEX UNIQ_1C52F958F98F144A ON brand');
        $this->addSql('ALTER TABLE brand DROP logo_id');
        $this->addSql('ALTER TABLE supplier DROP FOREIGN KEY FK_9B2A6C7EF98F144A');
        $this->addSql('DROP INDEX UNIQ_9B2A6C7EF98F144A ON supplier');
        $this->addSql('ALTER TABLE supplier DROP logo_id');
    }
}
