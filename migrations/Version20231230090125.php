<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231230090125 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product ADD supply_price_ht DOUBLE PRECISION DEFAULT NULL, ADD supply_price_ttc DOUBLE PRECISION DEFAULT NULL, ADD whole_sale_price_ht DOUBLE PRECISION DEFAULT NULL, ADD whole_sale_price_ttc DOUBLE PRECISION DEFAULT NULL, ADD price_ht DOUBLE PRECISION NOT NULL, ADD price_ttc DOUBLE PRECISION DEFAULT NULL, ADD whole_sale_moq INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product DROP supply_price_ht, DROP supply_price_ttc, DROP whole_sale_price_ht, DROP whole_sale_price_ttc, DROP price_ht, DROP price_ttc, DROP whole_sale_moq');
    }
}
