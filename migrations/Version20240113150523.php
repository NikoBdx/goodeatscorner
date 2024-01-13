<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240113150523 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE family DROP FOREIGN KEY FK_A5E6215BF31F1A32');
        $this->addSql('DROP INDEX IDX_A5E6215BF31F1A32 ON family');
        $this->addSql('ALTER TABLE family CHANGE shelf_id_id shelf_id INT NOT NULL');
        $this->addSql('ALTER TABLE family ADD CONSTRAINT FK_A5E6215B7C12FBC0 FOREIGN KEY (shelf_id) REFERENCES shelf (id)');
        $this->addSql('CREATE INDEX IDX_A5E6215B7C12FBC0 ON family (shelf_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE family DROP FOREIGN KEY FK_A5E6215B7C12FBC0');
        $this->addSql('DROP INDEX IDX_A5E6215B7C12FBC0 ON family');
        $this->addSql('ALTER TABLE family CHANGE shelf_id shelf_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE family ADD CONSTRAINT FK_A5E6215BF31F1A32 FOREIGN KEY (shelf_id_id) REFERENCES shelf (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_A5E6215BF31F1A32 ON family (shelf_id_id)');
    }
}
