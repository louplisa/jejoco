<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190722152206 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE asso_event ADD organization_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE asso_event ADD CONSTRAINT FK_4BF415CF32C8A3DE FOREIGN KEY (organization_id) REFERENCES organization (id)');
        $this->addSql('CREATE INDEX IDX_4BF415CF32C8A3DE ON asso_event (organization_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE asso_event DROP FOREIGN KEY FK_4BF415CF32C8A3DE');
        $this->addSql('DROP INDEX IDX_4BF415CF32C8A3DE ON asso_event');
        $this->addSql('ALTER TABLE asso_event DROP organization_id');
    }
}
