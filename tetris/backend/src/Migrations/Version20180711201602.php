<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180711201602 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE board_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE game_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE player_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE board (id INT NOT NULL, positions JSON NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN board.positions IS \'(DC2Type:json_array)\'');
        $this->addSql('CREATE TABLE game (id INT NOT NULL, player_one_id INT NOT NULL, player_two_id INT DEFAULT NULL, active_player_id INT DEFAULT NULL, board_id INT NOT NULL, p_one_symbol VARCHAR(255) DEFAULT NULL, p_two_symbol VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_232B318C649A58CD ON game (player_one_id)');
        $this->addSql('CREATE INDEX IDX_232B318CFC6BF02 ON game (player_two_id)');
        $this->addSql('CREATE INDEX IDX_232B318C8F70B6EB ON game (active_player_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_232B318CE7EC5785 ON game (board_id)');
        $this->addSql('CREATE TABLE player (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C649A58CD FOREIGN KEY (player_one_id) REFERENCES player (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318CFC6BF02 FOREIGN KEY (player_two_id) REFERENCES player (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C8F70B6EB FOREIGN KEY (active_player_id) REFERENCES player (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318CE7EC5785 FOREIGN KEY (board_id) REFERENCES board (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE game DROP CONSTRAINT FK_232B318CE7EC5785');
        $this->addSql('ALTER TABLE game DROP CONSTRAINT FK_232B318C649A58CD');
        $this->addSql('ALTER TABLE game DROP CONSTRAINT FK_232B318CFC6BF02');
        $this->addSql('ALTER TABLE game DROP CONSTRAINT FK_232B318C8F70B6EB');
        $this->addSql('DROP SEQUENCE board_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE game_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE player_id_seq CASCADE');
        $this->addSql('DROP TABLE board');
        $this->addSql('DROP TABLE game');
        $this->addSql('DROP TABLE player');
    }
}
