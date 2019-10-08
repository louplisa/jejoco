<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190724100245 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE belong_to ADD PRIMARY KEY (user_id, organization_id)');
        $this->addSql('ALTER TABLE belong_to ADD CONSTRAINT FK_C86E276DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE belong_to ADD CONSTRAINT FK_C86E276D32C8A3DE FOREIGN KEY (organization_id) REFERENCES organization (id)');
        $this->addSql('CREATE INDEX IDX_C86E276DA76ED395 ON belong_to (user_id)');
        $this->addSql('CREATE INDEX IDX_C86E276D32C8A3DE ON belong_to (organization_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE belong_to DROP FOREIGN KEY FK_C86E276DA76ED395');
        $this->addSql('ALTER TABLE belong_to DROP FOREIGN KEY FK_C86E276D32C8A3DE');
        $this->addSql('DROP INDEX IDX_C86E276DA76ED395 ON belong_to');
        $this->addSql('DROP INDEX IDX_C86E276D32C8A3DE ON belong_to');
        $this->addSql('ALTER TABLE belong_to DROP PRIMARY KEY');
    }
}
