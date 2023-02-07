<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230126133246 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE item (id INT AUTO_INCREMENT NOT NULL, producto_id INT NOT NULL, pedido_id INT DEFAULT NULL, cantidad INT NOT NULL, INDEX IDX_1F1B251E7645698E (producto_id), INDEX IDX_1F1B251E4854653A (pedido_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pedido (id INT AUTO_INCREMENT NOT NULL, fecha_pedido DATETIME DEFAULT NULL, id_usuario INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251E7645698E FOREIGN KEY (producto_id) REFERENCES producto (id)');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251E4854653A FOREIGN KEY (pedido_id) REFERENCES pedido (id)');
        $this->addSql('ALTER TABLE user CHANGE nombre nombre VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251E7645698E');
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251E4854653A');
        $this->addSql('DROP TABLE item');
        $this->addSql('DROP TABLE pedido');
        $this->addSql('ALTER TABLE user CHANGE nombre nombre VARCHAR(255) NOT NULL');
    }
}
