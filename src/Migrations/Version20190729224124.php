<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190729224124 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE belong_to_organizations');
        $this->addSql('ALTER TABLE organization ADD belong_to_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE organization ADD CONSTRAINT FK_C1EE637C568163B1 FOREIGN KEY (belong_to_id) REFERENCES belong_to (id)');
        $this->addSql('CREATE INDEX IDX_C1EE637C568163B1 ON organization (belong_to_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE belong_to_organizations (belong_to_id INT NOT NULL, organizations_id INT NOT NULL, INDEX IDX_C20F9CAD568163B1 (belong_to_id), INDEX IDX_C20F9CAD86288A55 (organizations_id), PRIMARY KEY(belong_to_id, organizations_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE belong_to_organizations ADD CONSTRAINT FK_C20F9CAD568163B1 FOREIGN KEY (belong_to_id) REFERENCES belong_to (id)');
        $this->addSql('ALTER TABLE belong_to_organizations ADD CONSTRAINT FK_C20F9CAD86288A55 FOREIGN KEY (organizations_id) REFERENCES organization (id)');
        $this->addSql('ALTER TABLE organization DROP FOREIGN KEY FK_C1EE637C568163B1');
        $this->addSql('DROP INDEX IDX_C1EE637C568163B1 ON organization');
        $this->addSql('ALTER TABLE organization DROP belong_to_id');
    }
}
