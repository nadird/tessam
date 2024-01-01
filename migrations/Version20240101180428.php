<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240101180428 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE product_combination (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, price_ht DOUBLE PRECISION NOT NULL, INDEX IDX_722684B04584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_combination_attribute_value (product_combination_id INT NOT NULL, attribute_value_id INT NOT NULL, INDEX IDX_5849B002EFDE3E0 (product_combination_id), INDEX IDX_5849B0065A22152 (attribute_value_id), PRIMARY KEY(product_combination_id, attribute_value_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_combination_image (product_combination_id INT NOT NULL, image_id INT NOT NULL, INDEX IDX_C385A1382EFDE3E0 (product_combination_id), INDEX IDX_C385A1383DA5256D (image_id), PRIMARY KEY(product_combination_id, image_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product_combination ADD CONSTRAINT FK_722684B04584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product_combination_attribute_value ADD CONSTRAINT FK_5849B002EFDE3E0 FOREIGN KEY (product_combination_id) REFERENCES product_combination (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_combination_attribute_value ADD CONSTRAINT FK_5849B0065A22152 FOREIGN KEY (attribute_value_id) REFERENCES attribute_value (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_combination_image ADD CONSTRAINT FK_C385A1382EFDE3E0 FOREIGN KEY (product_combination_id) REFERENCES product_combination (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_combination_image ADD CONSTRAINT FK_C385A1383DA5256D FOREIGN KEY (image_id) REFERENCES image (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_combination DROP FOREIGN KEY FK_722684B04584665A');
        $this->addSql('ALTER TABLE product_combination_attribute_value DROP FOREIGN KEY FK_5849B002EFDE3E0');
        $this->addSql('ALTER TABLE product_combination_attribute_value DROP FOREIGN KEY FK_5849B0065A22152');
        $this->addSql('ALTER TABLE product_combination_image DROP FOREIGN KEY FK_C385A1382EFDE3E0');
        $this->addSql('ALTER TABLE product_combination_image DROP FOREIGN KEY FK_C385A1383DA5256D');
        $this->addSql('DROP TABLE product_combination');
        $this->addSql('DROP TABLE product_combination_attribute_value');
        $this->addSql('DROP TABLE product_combination_image');
    }
}
