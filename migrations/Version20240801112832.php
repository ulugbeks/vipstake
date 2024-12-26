<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240801112832 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE vip_review ADD odds_margin VARCHAR(255) DEFAULT NULL, ADD wagering VARCHAR(255) DEFAULT NULL, ADD withdrawal_time VARCHAR(255) DEFAULT NULL, ADD bonus_max_bet VARCHAR(255) DEFAULT NULL, ADD payment_logos LONGTEXT DEFAULT NULL, ADD listing_text LONGTEXT DEFAULT NULL, ADD labels LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE vip_review DROP odds_margin, DROP wagering, DROP withdrawal_time, DROP bonus_max_bet, DROP payment_logos, DROP listing_text, DROP labels');
    }
}
