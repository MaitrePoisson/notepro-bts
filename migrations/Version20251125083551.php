<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251125083551 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE class_level (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evaluation (id INT AUTO_INCREMENT NOT NULL, professor_id INT NOT NULL, subject_id INT NOT NULL, class_level_id INT NOT NULL, date DATETIME NOT NULL, label VARCHAR(255) NOT NULL, bareme INT NOT NULL, INDEX IDX_1323A5757D2D84D5 (professor_id), INDEX IDX_1323A57523EDC87 (subject_id), INDEX IDX_1323A575EB7F80F7 (class_level_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE grade (id INT AUTO_INCREMENT NOT NULL, evaluation_id INT NOT NULL, student_id INT NOT NULL, grade NUMERIC(5, 2) DEFAULT NULL, present TINYINT(1) NOT NULL, INDEX IDX_595AAE34456C5646 (evaluation_id), INDEX IDX_595AAE34CB944F1A (student_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `previous_passwords` (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, INDEX IDX_EAF00417A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subject (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, class_level_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, name VARCHAR(255) DEFAULT NULL, is_verified TINYINT(1) NOT NULL, password_change_date DATETIME NOT NULL, discr VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D649EB7F80F7 (class_level_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE professor_class_level (professor_id INT NOT NULL, class_level_id INT NOT NULL, INDEX IDX_92118DC57D2D84D5 (professor_id), INDEX IDX_92118DC5EB7F80F7 (class_level_id), PRIMARY KEY(professor_id, class_level_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE professor_subject (professor_id INT NOT NULL, subject_id INT NOT NULL, INDEX IDX_A4E1512E7D2D84D5 (professor_id), INDEX IDX_A4E1512E23EDC87 (subject_id), PRIMARY KEY(professor_id, subject_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE evaluation ADD CONSTRAINT FK_1323A5757D2D84D5 FOREIGN KEY (professor_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE evaluation ADD CONSTRAINT FK_1323A57523EDC87 FOREIGN KEY (subject_id) REFERENCES subject (id)');
        $this->addSql('ALTER TABLE evaluation ADD CONSTRAINT FK_1323A575EB7F80F7 FOREIGN KEY (class_level_id) REFERENCES class_level (id)');
        $this->addSql('ALTER TABLE grade ADD CONSTRAINT FK_595AAE34456C5646 FOREIGN KEY (evaluation_id) REFERENCES evaluation (id)');
        $this->addSql('ALTER TABLE grade ADD CONSTRAINT FK_595AAE34CB944F1A FOREIGN KEY (student_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE `previous_passwords` ADD CONSTRAINT FK_EAF00417A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D649EB7F80F7 FOREIGN KEY (class_level_id) REFERENCES class_level (id)');
        $this->addSql('ALTER TABLE professor_class_level ADD CONSTRAINT FK_92118DC57D2D84D5 FOREIGN KEY (professor_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE professor_class_level ADD CONSTRAINT FK_92118DC5EB7F80F7 FOREIGN KEY (class_level_id) REFERENCES class_level (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE professor_subject ADD CONSTRAINT FK_A4E1512E7D2D84D5 FOREIGN KEY (professor_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE professor_subject ADD CONSTRAINT FK_A4E1512E23EDC87 FOREIGN KEY (subject_id) REFERENCES subject (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evaluation DROP FOREIGN KEY FK_1323A5757D2D84D5');
        $this->addSql('ALTER TABLE evaluation DROP FOREIGN KEY FK_1323A57523EDC87');
        $this->addSql('ALTER TABLE evaluation DROP FOREIGN KEY FK_1323A575EB7F80F7');
        $this->addSql('ALTER TABLE grade DROP FOREIGN KEY FK_595AAE34456C5646');
        $this->addSql('ALTER TABLE grade DROP FOREIGN KEY FK_595AAE34CB944F1A');
        $this->addSql('ALTER TABLE `previous_passwords` DROP FOREIGN KEY FK_EAF00417A76ED395');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D649EB7F80F7');
        $this->addSql('ALTER TABLE professor_class_level DROP FOREIGN KEY FK_92118DC57D2D84D5');
        $this->addSql('ALTER TABLE professor_class_level DROP FOREIGN KEY FK_92118DC5EB7F80F7');
        $this->addSql('ALTER TABLE professor_subject DROP FOREIGN KEY FK_A4E1512E7D2D84D5');
        $this->addSql('ALTER TABLE professor_subject DROP FOREIGN KEY FK_A4E1512E23EDC87');
        $this->addSql('DROP TABLE class_level');
        $this->addSql('DROP TABLE evaluation');
        $this->addSql('DROP TABLE grade');
        $this->addSql('DROP TABLE `previous_passwords`');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE subject');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE professor_class_level');
        $this->addSql('DROP TABLE professor_subject');
    }
}
