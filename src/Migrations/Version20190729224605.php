<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190729224605 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE organization DROP FOREIGN KEY FK_C1EE637C568163B1');
        $this->addSql('DROP INDEX IDX_C1EE637C568163B1 ON organization');
        $this->addSql('ALTER TABLE organization DROP belong_to_id');
        $this->addSql('ALTER TABLE belong_to ADD belongTos INT DEFAULT NULL');
        $this->addSql('ALTER TABLE belong_to ADD CONSTRAINT FK_C86E276D790C5F59 FOREIGN KEY (belongTos) REFERENCES organization (id)');
        $this->addSql('CREATE INDEX IDX_C86E276D790C5F59 ON belong_to (belongTos)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE belong_to DROP FOREIGN KEY FK_C86E276D790C5F59');
        $this->addSql('DROP INDEX IDX_C86E276D790C5F59 ON belong_to');
        $this->addSql('ALTER TABLE belong_to DROP belongTos');
        $this->addSql('ALTER TABLE organization ADD belong_to_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE organization ADD CONSTRAINT FK_C1EE637C568163B1 FOREIGN KEY (belong_to_id) REFERENCES belong_to (id)');
        $this->addSql('CREATE INDEX IDX_C1EE637C568163B1 ON organization (belong_to_id)');
    }
}
