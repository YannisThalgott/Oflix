<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220510080305 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE movie_genre (movie_id INT NOT NULL, genre_id INT NOT NULL, INDEX IDX_FD1229648F93B6FC (movie_id), INDEX IDX_FD1229644296D31F (genre_id), PRIMARY KEY(movie_id, genre_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE movie_genre ADD CONSTRAINT FK_FD1229648F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE movie_genre ADD CONSTRAINT FK_FD1229644296D31F FOREIGN KEY (genre_id) REFERENCES genre (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE casting_person');
        $this->addSql('DROP TABLE genre_movie');
        $this->addSql('ALTER TABLE casting DROP INDEX UNIQ_D11BBA508F93B6FC, ADD INDEX IDX_D11BBA508F93B6FC (movie_id)');
        $this->addSql('ALTER TABLE casting ADD person_id INT DEFAULT NULL, CHANGE movie_id movie_id INT DEFAULT NULL, CHANGE role role VARCHAR(100) NOT NULL, CHANGE credit_order credit_order SMALLINT NOT NULL');
        $this->addSql('ALTER TABLE casting ADD CONSTRAINT FK_D11BBA50217BBB47 FOREIGN KEY (person_id) REFERENCES person (id)');
        $this->addSql('CREATE INDEX IDX_D11BBA50217BBB47 ON casting (person_id)');
        $this->addSql('ALTER TABLE person CHANGE lastname lastname VARCHAR(50) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE casting_person (casting_id INT NOT NULL, person_id INT NOT NULL, INDEX IDX_DCB3973E217BBB47 (person_id), INDEX IDX_DCB3973E9EB2648F (casting_id), PRIMARY KEY(casting_id, person_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE genre_movie (genre_id INT NOT NULL, movie_id INT NOT NULL, INDEX IDX_A058EDAA8F93B6FC (movie_id), INDEX IDX_A058EDAA4296D31F (genre_id), PRIMARY KEY(genre_id, movie_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE casting_person ADD CONSTRAINT FK_DCB3973E217BBB47 FOREIGN KEY (person_id) REFERENCES person (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE casting_person ADD CONSTRAINT FK_DCB3973E9EB2648F FOREIGN KEY (casting_id) REFERENCES casting (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE genre_movie ADD CONSTRAINT FK_A058EDAA4296D31F FOREIGN KEY (genre_id) REFERENCES genre (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE genre_movie ADD CONSTRAINT FK_A058EDAA8F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE movie_genre');
        $this->addSql('ALTER TABLE casting DROP INDEX IDX_D11BBA508F93B6FC, ADD UNIQUE INDEX UNIQ_D11BBA508F93B6FC (movie_id)');
        $this->addSql('ALTER TABLE casting DROP FOREIGN KEY FK_D11BBA50217BBB47');
        $this->addSql('DROP INDEX IDX_D11BBA50217BBB47 ON casting');
        $this->addSql('ALTER TABLE casting DROP person_id, CHANGE movie_id movie_id INT NOT NULL, CHANGE role role VARCHAR(50) NOT NULL, CHANGE credit_order credit_order INT NOT NULL');
        $this->addSql('ALTER TABLE person CHANGE lastname lastname VARCHAR(50) NOT NULL');
    }
}
