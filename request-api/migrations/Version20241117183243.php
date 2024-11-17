<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241117183243 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE event.received (uuid UUID NOT NULL, received_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(uuid))');
        $this->addSql('COMMENT ON COLUMN event.received.uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN event.received.received_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE request (uuid UUID NOT NULL, agent_id INT NOT NULL, requester_id INT NOT NULL, PRIMARY KEY(uuid))');
        $this->addSql('COMMENT ON COLUMN request.uuid IS \'(DC2Type:uuid)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE event.received');
        $this->addSql('DROP TABLE request');
    }
}
