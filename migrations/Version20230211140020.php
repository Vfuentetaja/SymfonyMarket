<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230211140020 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pregunta ADD CONSTRAINT FK_AEE0E1F77645698E FOREIGN KEY (producto_id) REFERENCES producto (id)');
        $this->addSql('CREATE INDEX IDX_AEE0E1F77645698E ON pregunta (producto_id)');
        $this->addSql('ALTER TABLE respuesta ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE respuesta ADD CONSTRAINT FK_6C6EC5EEA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_6C6EC5EEA76ED395 ON respuesta (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pregunta DROP FOREIGN KEY FK_AEE0E1F77645698E');
        $this->addSql('DROP INDEX IDX_AEE0E1F77645698E ON pregunta');
        $this->addSql('ALTER TABLE respuesta DROP FOREIGN KEY FK_6C6EC5EEA76ED395');
        $this->addSql('DROP INDEX IDX_6C6EC5EEA76ED395 ON respuesta');
        $this->addSql('ALTER TABLE respuesta DROP user_id');
    }
}
