<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240101175913 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE attribute_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE attribute_value (id INT AUTO_INCREMENT NOT NULL, type_id INT DEFAULT NULL, value VARCHAR(255) NOT NULL, INDEX IDX_FE4FBB82C54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE feature_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE feature_value (id INT AUTO_INCREMENT NOT NULL, type_id INT DEFAULT NULL, value VARCHAR(255) NOT NULL, INDEX IDX_D429523DC54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_family (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, is_deleted TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_family_attribute_type (product_family_id INT NOT NULL, attribute_type_id INT NOT NULL, INDEX IDX_91CF1CF9ADFEE0E7 (product_family_id), INDEX IDX_91CF1CF94ED0D557 (attribute_type_id), PRIMARY KEY(product_family_id, attribute_type_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_family_feature_type (product_family_id INT NOT NULL, feature_type_id INT NOT NULL, INDEX IDX_30C8E655ADFEE0E7 (product_family_id), INDEX IDX_30C8E655F45B179A (feature_type_id), PRIMARY KEY(product_family_id, feature_type_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shop_warehouse (shop_id INT NOT NULL, warehouse_id INT NOT NULL, INDEX IDX_F50405774D16C4DD (shop_id), INDEX IDX_F50405775080ECDE (warehouse_id), PRIMARY KEY(shop_id, warehouse_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shop_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, color VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE attribute_value ADD CONSTRAINT FK_FE4FBB82C54C8C93 FOREIGN KEY (type_id) REFERENCES attribute_type (id)');
        $this->addSql('ALTER TABLE feature_value ADD CONSTRAINT FK_D429523DC54C8C93 FOREIGN KEY (type_id) REFERENCES feature_type (id)');
        $this->addSql('ALTER TABLE product_family_attribute_type ADD CONSTRAINT FK_91CF1CF9ADFEE0E7 FOREIGN KEY (product_family_id) REFERENCES product_family (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_family_attribute_type ADD CONSTRAINT FK_91CF1CF94ED0D557 FOREIGN KEY (attribute_type_id) REFERENCES attribute_type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_family_feature_type ADD CONSTRAINT FK_30C8E655ADFEE0E7 FOREIGN KEY (product_family_id) REFERENCES product_family (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_family_feature_type ADD CONSTRAINT FK_30C8E655F45B179A FOREIGN KEY (feature_type_id) REFERENCES feature_type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE shop_warehouse ADD CONSTRAINT FK_F50405774D16C4DD FOREIGN KEY (shop_id) REFERENCES shop (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE shop_warehouse ADD CONSTRAINT FK_F50405775080ECDE FOREIGN KEY (warehouse_id) REFERENCES warehouse (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE shop ADD main_warehouse_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE shop ADD CONSTRAINT FK_AC6A4CA249385967 FOREIGN KEY (main_warehouse_id) REFERENCES warehouse (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_AC6A4CA249385967 ON shop (main_warehouse_id)');
        $this->addSql('ALTER TABLE warehouse ADD is_shop_ware_house TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE attribute_value DROP FOREIGN KEY FK_FE4FBB82C54C8C93');
        $this->addSql('ALTER TABLE feature_value DROP FOREIGN KEY FK_D429523DC54C8C93');
        $this->addSql('ALTER TABLE product_family_attribute_type DROP FOREIGN KEY FK_91CF1CF9ADFEE0E7');
        $this->addSql('ALTER TABLE product_family_attribute_type DROP FOREIGN KEY FK_91CF1CF94ED0D557');
        $this->addSql('ALTER TABLE product_family_feature_type DROP FOREIGN KEY FK_30C8E655ADFEE0E7');
        $this->addSql('ALTER TABLE product_family_feature_type DROP FOREIGN KEY FK_30C8E655F45B179A');
        $this->addSql('ALTER TABLE shop_warehouse DROP FOREIGN KEY FK_F50405774D16C4DD');
        $this->addSql('ALTER TABLE shop_warehouse DROP FOREIGN KEY FK_F50405775080ECDE');
        $this->addSql('DROP TABLE attribute_type');
        $this->addSql('DROP TABLE attribute_value');
        $this->addSql('DROP TABLE feature_type');
        $this->addSql('DROP TABLE feature_value');
        $this->addSql('DROP TABLE product_family');
        $this->addSql('DROP TABLE product_family_attribute_type');
        $this->addSql('DROP TABLE product_family_feature_type');
        $this->addSql('DROP TABLE shop_warehouse');
        $this->addSql('DROP TABLE shop_type');
        $this->addSql('ALTER TABLE warehouse DROP is_shop_ware_house');
        $this->addSql('ALTER TABLE shop DROP FOREIGN KEY FK_AC6A4CA249385967');
        $this->addSql('DROP INDEX UNIQ_AC6A4CA249385967 ON shop');
        $this->addSql('ALTER TABLE shop DROP main_warehouse_id');
    }
}
