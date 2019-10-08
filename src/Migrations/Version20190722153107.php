<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190722153107 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE organization_partner_relations (asso_event_id INT NOT NULL, organization_id INT NOT NULL, INDEX IDX_7E49B5B271D7C417 (asso_event_id), INDEX IDX_7E49B5B232C8A3DE (organization_id), PRIMARY KEY(asso_event_id, organization_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE organization_partner_relations ADD CONSTRAINT FK_7E49B5B271D7C417 FOREIGN KEY (asso_event_id) REFERENCES asso_event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE organization_partner_relations ADD CONSTRAINT FK_7E49B5B232C8A3DE FOREIGN KEY (organization_id) REFERENCES organization (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE organization_partner_relations');
    }
}
