<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190724165949 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user ADD is_admin TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE belong_to MODIFY id INT NOT NULL');
        $this->addSql('DROP INDEX user_association_unique ON belong_to');
        $this->addSql('ALTER TABLE belong_to DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE belong_to DROP id, DROP is_admin');
        $this->addSql('ALTER TABLE belong_to ADD PRIMARY KEY (user_id, organization_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE belong_to DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE belong_to ADD id INT AUTO_INCREMENT NOT NULL, ADD is_admin TINYINT(1) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX user_association_unique ON belong_to (user_id, organization_id)');
        $this->addSql('ALTER TABLE belong_to ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE user DROP is_admin');
    }
}
