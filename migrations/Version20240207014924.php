<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240207014924 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE transactions (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, payee_id INTEGER DEFAULT NULL, payeer_id INTEGER DEFAULT NULL, amount DOUBLE PRECISION NOT NULL, operation_type VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, CONSTRAINT FK_EAA81A4CCB4B68F FOREIGN KEY (payee_id) REFERENCES users (id), CONSTRAINT FK_EAA81A4C22C6F047 FOREIGN KEY (payeer_id) REFERENCES users (id))');
        $this->addSql('CREATE INDEX IDX_EAA81A4CCB4B68F ON transactions (payee_id)');
        $this->addSql('CREATE INDEX IDX_EAA81A4C22C6F047 ON transactions (payeer_id)');

        $this->addSql('CREATE TABLE users (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(150) NOT NULL, ssn VARCHAR(150) NOT NULL, email VARCHAR(150) NOT NULL, password VARCHAR(150) NOT NULL, role VARCHAR(150) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E97EE6971 ON users (ssn)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9E7927C74 ON users (email)');

        $this->addSql('CREATE TABLE wallets (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, account_balance DOUBLE PRECISION NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, CONSTRAINT FK_967AAA6CA76ED395 FOREIGN KEY (user_id) REFERENCES users (id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_967AAA6CA76ED395 ON wallets (user_id)');
    }


    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE transactions');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE wallets');
    }
}
