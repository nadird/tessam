<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231230083052 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE image_resized (id INT AUTO_INCREMENT NOT NULL, image_id INT DEFAULT NULL, type_id INT DEFAULT NULL, url VARCHAR(255) NOT NULL, INDEX IDX_D8DD22FA3DA5256D (image_id), INDEX IDX_D8DD22FAC54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image_type (id INT AUTO_INCREMENT NOT NULL, height INT NOT NULL, width INT NOT NULL, is_product TINYINT(1) NOT NULL, is_category TINYINT(1) NOT NULL, is_brand TINYINT(1) NOT NULL, is_blog_category TINYINT(1) NOT NULL, is_blog_post TINYINT(1) NOT NULL, is_employee TINYINT(1) NOT NULL, is_supplier TINYINT(1) NOT NULL, is_shop TINYINT(1) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE image_resized ADD CONSTRAINT FK_D8DD22FA3DA5256D FOREIGN KEY (image_id) REFERENCES image (id)');
        $this->addSql('ALTER TABLE image_resized ADD CONSTRAINT FK_D8DD22FAC54C8C93 FOREIGN KEY (type_id) REFERENCES image_type (id)');
        $this->addSql('ALTER TABLE image ADD name VARCHAR(255) NOT NULL, ADD is_deleted TINYINT(1) NOT NULL, ADD date_add DATETIME NOT NULL, ADD alt VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE image_resized DROP FOREIGN KEY FK_D8DD22FA3DA5256D');
        $this->addSql('ALTER TABLE image_resized DROP FOREIGN KEY FK_D8DD22FAC54C8C93');
        $this->addSql('DROP TABLE image_resized');
        $this->addSql('DROP TABLE image_type');
        $this->addSql('ALTER TABLE image DROP name, DROP is_deleted, DROP date_add, DROP alt');
    }
}
