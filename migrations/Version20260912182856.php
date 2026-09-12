<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260912182856 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE game (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, image_url VARCHAR(255) NOT NULL, price_paid BIGINT DEFAULT NULL, steam_user_id BIGINT NOT NULL, steam_game_id BIGINT NOT NULL, create_dt DATETIME NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE synchronization (id INT AUTO_INCREMENT NOT NULL, steam_user_id BIGINT NOT NULL, create_dt DATETIME NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE synchronization_game (id INT AUTO_INCREMENT NOT NULL, playtime_forever BIGINT NOT NULL, playtime_two_weeks BIGINT NOT NULL, last_played DATETIME NOT NULL, synchronization_id INT NOT NULL, game_id INT NOT NULL, INDEX IDX_DFEA0D46C469E18 (synchronization_id), INDEX IDX_DFEA0D46E48FD905 (game_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE synchronization_game ADD CONSTRAINT FK_DFEA0D46C469E18 FOREIGN KEY (synchronization_id) REFERENCES synchronization (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE synchronization_game ADD CONSTRAINT FK_DFEA0D46E48FD905 FOREIGN KEY (game_id) REFERENCES game (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE synchronization_game DROP FOREIGN KEY FK_DFEA0D46C469E18');
        $this->addSql('ALTER TABLE synchronization_game DROP FOREIGN KEY FK_DFEA0D46E48FD905');
        $this->addSql('DROP TABLE game');
        $this->addSql('DROP TABLE synchronization');
        $this->addSql('DROP TABLE synchronization_game');
    }
}
