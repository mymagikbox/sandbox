<?php

declare(strict_types=1);

namespace UserMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240805132852 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('
CREATE TABLE refresh_token (
    id VARCHAR(255) NOT NULL, 
    user_id INT DEFAULT NULL, 
    refresh_token VARCHAR(512) NOT NULL, 
    refresh_count INT NOT NULL, 
    expired_at DATETIME NOT NULL, 
    device_info VARCHAR(255) NULL, 
    UNIQUE INDEX UNIQ_C74F2195A76ED395 (user_id), 
    PRIMARY KEY(id)) 
    DEFAULT CHARACTER SET utf8');

        $this->addSql('
CREATE TABLE user (
    id INT AUTO_INCREMENT NOT NULL, 
    username VARCHAR(255) NOT NULL, 
    email VARCHAR(255) NOT NULL, 
    password_hash VARCHAR(255) NOT NULL, 
    status VARCHAR(255) NOT NULL, 
    role VARCHAR(255) NOT NULL, 
    created_at DATETIME NOT NULL, 
    updated_at DATETIME DEFAULT NULL, 
    deleted_at DATETIME DEFAULT NULL, 
    UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), 
    INDEX username_idx (username), 
    INDEX email_idx (email), 
    PRIMARY KEY(id)) 
    DEFAULT CHARACTER SET utf8');

        $this->addSql('ALTER TABLE refresh_token ADD CONSTRAINT FK_C74F2195A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE refresh_token DROP FOREIGN KEY FK_C74F2195A76ED395');
        $this->addSql('DROP TABLE refresh_token');
        $this->addSql('DROP TABLE user');
    }
}
