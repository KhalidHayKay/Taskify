<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240514172628 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contact_persons ADD user_id INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE contact_persons ADD CONSTRAINT FK_3873E652A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3873E652A76ED395 ON contact_persons (user_id)');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E94F8A983C');
        $this->addSql('DROP INDEX UNIQ_1483A5E94F8A983C ON users');
        $this->addSql('ALTER TABLE users CHANGE contact_person_id user_id INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E9A76ED395 FOREIGN KEY (user_id) REFERENCES contact_persons (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9A76ED395 ON users (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E9A76ED395');
        $this->addSql('DROP INDEX UNIQ_1483A5E9A76ED395 ON users');
        $this->addSql('ALTER TABLE users CHANGE user_id contact_person_id INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E94F8A983C FOREIGN KEY (contact_person_id) REFERENCES contact_persons (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E94F8A983C ON users (contact_person_id)');
        $this->addSql('ALTER TABLE contact_persons DROP FOREIGN KEY FK_3873E652A76ED395');
        $this->addSql('DROP INDEX UNIQ_3873E652A76ED395 ON contact_persons');
        $this->addSql('ALTER TABLE contact_persons DROP user_id');
    }
}
