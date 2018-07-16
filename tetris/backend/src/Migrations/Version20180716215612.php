<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180716215612 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE game DROP CONSTRAINT fk_232b318c8f70b6eb');
        $this->addSql('DROP INDEX idx_232b318c8f70b6eb');
        $this->addSql('ALTER TABLE game ADD active_player INT NOT NULL');
        $this->addSql('ALTER TABLE game DROP active_player_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE game ADD active_player_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE game DROP active_player');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT fk_232b318c8f70b6eb FOREIGN KEY (active_player_id) REFERENCES player (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_232b318c8f70b6eb ON game (active_player_id)');
    }
}
