<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190729210816 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE belong_to_organization (belong_to_id INT NOT NULL, organization_id INT NOT NULL, INDEX IDX_D7097AE9568163B1 (belong_to_id), INDEX IDX_D7097AE932C8A3DE (organization_id), PRIMARY KEY(belong_to_id, organization_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE belong_to_organization ADD CONSTRAINT FK_D7097AE9568163B1 FOREIGN KEY (belong_to_id) REFERENCES belong_to (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE belong_to_organization ADD CONSTRAINT FK_D7097AE932C8A3DE FOREIGN KEY (organization_id) REFERENCES organization (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE belong_to DROP FOREIGN KEY FK_C86E276D32C8A3DE');
        $this->addSql('DROP INDEX IDX_C86E276D32C8A3DE ON belong_to');
        $this->addSql('ALTER TABLE belong_to DROP organization_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE belong_to_organization');
        $this->addSql('ALTER TABLE belong_to ADD organization_id INT NOT NULL');
        $this->addSql('ALTER TABLE belong_to ADD CONSTRAINT FK_C86E276D32C8A3DE FOREIGN KEY (organization_id) REFERENCES organization (id)');
        $this->addSql('CREATE INDEX IDX_C86E276D32C8A3DE ON belong_to (organization_id)');
    }
}
