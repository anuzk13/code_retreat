<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180724214241 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE UNIQUE INDEX player_one ON game (player_one_id) WHERE (active is true)');
        $this->addSql('CREATE UNIQUE INDEX player_two ON game (player_two_id) WHERE (active is true)');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT different_players CHECK ( player_one_id != player_two_id )');

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX player_one');
        $this->addSql('DROP INDEX player_two');
    }
}
