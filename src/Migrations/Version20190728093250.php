<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190728093250 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE belong_to (user_id INT NOT NULL, organization_id INT NOT NULL, INDEX IDX_C86E276DA76ED395 (user_id), INDEX IDX_C86E276D32C8A3DE (organization_id), UNIQUE INDEX unique_user_organization (user_id, organization_id), PRIMARY KEY(user_id, organization_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE belong_to ADD CONSTRAINT FK_C86E276DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE belong_to ADD CONSTRAINT FK_C86E276D32C8A3DE FOREIGN KEY (organization_id) REFERENCES organization (id)');
        $this->addSql('DROP TABLE belong_to_rights');
        $this->addSql('DROP TABLE belong_to_temporary');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE belong_to_rights (user_id INT NOT NULL, organization_id INT NOT NULL, INDEX IDX_9D6021EEA76ED395 (user_id), INDEX IDX_9D6021EE32C8A3DE (organization_id), PRIMARY KEY(user_id, organization_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE belong_to_temporary (user_id INT NOT NULL, organization_id INT NOT NULL, INDEX IDX_201951EEA76ED395 (user_id), INDEX IDX_201951EE32C8A3DE (organization_id), PRIMARY KEY(user_id, organization_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE belong_to_rights ADD CONSTRAINT FK_9D6021EE32C8A3DE FOREIGN KEY (organization_id) REFERENCES organization (id)');
        $this->addSql('ALTER TABLE belong_to_rights ADD CONSTRAINT FK_9D6021EEA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE belong_to_temporary ADD CONSTRAINT FK_201951EE32C8A3DE FOREIGN KEY (organization_id) REFERENCES organization (id)');
        $this->addSql('ALTER TABLE belong_to_temporary ADD CONSTRAINT FK_201951EEA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('DROP TABLE belong_to');
    }
}
