<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240710132140 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE credentials (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, plugin_id INT DEFAULT NULL, credentials LONGTEXT NOT NULL COMMENT \'(DC2Type:object)\', INDEX IDX_FA05280EA76ED395 (user_id), INDEX IDX_FA05280EEC942BCF (plugin_id), UNIQUE INDEX UNIQ_IDENTIFIER_USER_PLUGIN (plugin_id, user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE plugin (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, image_name VARCHAR(255) DEFAULT NULL, dashboard_path VARCHAR(255) NOT NULL, credentials_form_fields LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', setup LONGTEXT DEFAULT NULL, UNIQUE INDEX UNIQ_E96E27941C24E40D (dashboard_path), INDEX IDX_E96E279412469DE2 (category_id), UNIQUE INDEX UNIQ_IDENTIFIER_DASHBOARD_PATH_NAME (dashboard_path, name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE plugin_user (plugin_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_4201E8AEC942BCF (plugin_id), INDEX IDX_4201E8AA76ED395 (user_id), PRIMARY KEY(plugin_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE plugin_screen_shots (id INT AUTO_INCREMENT NOT NULL, plugin_id INT NOT NULL, image_name VARCHAR(255) DEFAULT NULL, updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_1162A0C3EC942BCF (plugin_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rating (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, plugin_id INT DEFAULT NULL, rating INT NOT NULL, comment LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_D8892622A76ED395 (user_id), INDEX IDX_D8892622EC942BCF (plugin_id), UNIQUE INDEX UNIQ_IDENTIFIER_RATING_USER_PLUGIN (user_id, plugin_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE credentials ADD CONSTRAINT FK_FA05280EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE credentials ADD CONSTRAINT FK_FA05280EEC942BCF FOREIGN KEY (plugin_id) REFERENCES plugin (id)');
        $this->addSql('ALTER TABLE plugin ADD CONSTRAINT FK_E96E279412469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE plugin_user ADD CONSTRAINT FK_4201E8AEC942BCF FOREIGN KEY (plugin_id) REFERENCES plugin (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE plugin_user ADD CONSTRAINT FK_4201E8AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE plugin_screen_shots ADD CONSTRAINT FK_1162A0C3EC942BCF FOREIGN KEY (plugin_id) REFERENCES plugin (id)');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_D8892622A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_D8892622EC942BCF FOREIGN KEY (plugin_id) REFERENCES plugin (id)');
        $this->addSql('ALTER TABLE user ADD username VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE credentials DROP FOREIGN KEY FK_FA05280EA76ED395');
        $this->addSql('ALTER TABLE credentials DROP FOREIGN KEY FK_FA05280EEC942BCF');
        $this->addSql('ALTER TABLE plugin DROP FOREIGN KEY FK_E96E279412469DE2');
        $this->addSql('ALTER TABLE plugin_user DROP FOREIGN KEY FK_4201E8AEC942BCF');
        $this->addSql('ALTER TABLE plugin_user DROP FOREIGN KEY FK_4201E8AA76ED395');
        $this->addSql('ALTER TABLE plugin_screen_shots DROP FOREIGN KEY FK_1162A0C3EC942BCF');
        $this->addSql('ALTER TABLE rating DROP FOREIGN KEY FK_D8892622A76ED395');
        $this->addSql('ALTER TABLE rating DROP FOREIGN KEY FK_D8892622EC942BCF');
        $this->addSql('DROP TABLE credentials');
        $this->addSql('DROP TABLE plugin');
        $this->addSql('DROP TABLE plugin_user');
        $this->addSql('DROP TABLE plugin_screen_shots');
        $this->addSql('DROP TABLE rating');
        $this->addSql('ALTER TABLE user DROP username');
    }
}
