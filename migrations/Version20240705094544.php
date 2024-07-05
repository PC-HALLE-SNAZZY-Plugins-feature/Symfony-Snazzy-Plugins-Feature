<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240705094544 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE credentials (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, plugin_id INT DEFAULT NULL, credentials LONGTEXT NOT NULL COMMENT \'(DC2Type:object)\', INDEX IDX_FA05280EA76ED395 (user_id), INDEX IDX_FA05280EEC942BCF (plugin_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE credentials ADD CONSTRAINT FK_FA05280EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE credentials ADD CONSTRAINT FK_FA05280EEC942BCF FOREIGN KEY (plugin_id) REFERENCES plugin (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE credentials DROP FOREIGN KEY FK_FA05280EA76ED395');
        $this->addSql('ALTER TABLE credentials DROP FOREIGN KEY FK_FA05280EEC942BCF');
        $this->addSql('DROP TABLE credentials');
    }
}
