<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180405205830 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE event_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE wallet_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE event (id INT NOT NULL, wallet_id INT NOT NULL, type SMALLINT NOT NULL, amount INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3BAE0AA7712520F3 ON event (wallet_id)');
        $this->addSql('CREATE TABLE wallet (id INT NOT NULL, balance INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7712520F3 FOREIGN KEY (wallet_id) REFERENCES wallet (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');

        for ($i = 0; $i < 3; $i++) {
            $this->addSql('INSERT INTO wallet (id, balance) VALUES (nextval(\'wallet_id_seq\'), ?)', [0]);
        }
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE event DROP CONSTRAINT FK_3BAE0AA7712520F3');
        $this->addSql('DROP SEQUENCE event_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE wallet_id_seq CASCADE');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE wallet');
    }
}
