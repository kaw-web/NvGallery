<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240331213252 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE inbounds (id INT AUTO_INCREMENT NOT NULL, inventory_id INT DEFAULT NULL, arrival_date DATETIME DEFAULT NULL, quantity DOUBLE PRECISION NOT NULL, INDEX IDX_F964252D9EEA759 (inventory_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE inbounds ADD CONSTRAINT FK_F964252D9EEA759 FOREIGN KEY (inventory_id) REFERENCES inventories (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE inbounds DROP FOREIGN KEY FK_F964252D9EEA759');
        $this->addSql('DROP TABLE inbounds');
    }
}
