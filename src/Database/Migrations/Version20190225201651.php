<?php

namespace Lebenlabs\SimpleCMS\Database\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20190225201651 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE lebenlabs_simplecms_categorias (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, destacada TINYINT(1) NOT NULL, publicada TINYINT(1) NOT NULL, protegida TINYINT(1) DEFAULT \'0\' NOT NULL, slug VARCHAR(128) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_7427DE6A3A909126 (nombre), UNIQUE INDEX UNIQ_7427DE6A989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lebenlabs_simplecms_imagenes (id INT AUTO_INCREMENT NOT NULL, filename VARCHAR(250) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_4C2A75E03C0BE965 (filename), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lebenlabs_simplecms_publicaciones (id INT AUTO_INCREMENT NOT NULL, categoria_id INT NOT NULL, imagen_id INT DEFAULT NULL, titulo VARCHAR(255) NOT NULL, slug VARCHAR(256) NOT NULL, extracto VARCHAR(1024) DEFAULT NULL, cuerpo LONGTEXT NOT NULL, publicada TINYINT(1) NOT NULL, protegida TINYINT(1) DEFAULT \'0\' NOT NULL, destacada TINYINT(1) NOT NULL, fecha_publicacion DATETIME NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_6D8562FF3397707A (categoria_id), INDEX IDX_6D8562FF763C8AA7 (imagen_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE lebenlabs_simplecms_publicaciones ADD CONSTRAINT FK_6D8562FF3397707A FOREIGN KEY (categoria_id) REFERENCES lebenlabs_simplecms_categorias (id)');
        $this->addSql('ALTER TABLE lebenlabs_simplecms_publicaciones ADD CONSTRAINT FK_6D8562FF763C8AA7 FOREIGN KEY (imagen_id) REFERENCES lebenlabs_simplecms_imagenes (id) ON DELETE SET NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE lebenlabs_simplecms_publicaciones DROP FOREIGN KEY FK_6D8562FF3397707A');
        $this->addSql('ALTER TABLE lebenlabs_simplecms_publicaciones DROP FOREIGN KEY FK_6D8562FF763C8AA7');
        $this->addSql('DROP TABLE lebenlabs_simplecms_categorias');
        $this->addSql('DROP TABLE lebenlabs_simplecms_imagenes');
        $this->addSql('DROP TABLE lebenlabs_simplecms_publicaciones');
    }
}
