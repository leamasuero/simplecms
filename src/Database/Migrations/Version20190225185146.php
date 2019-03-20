<?php

namespace Database\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20190225185146 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE lebenlabs_simplecms_menu_items (id INT AUTO_INCREMENT NOT NULL, menu_item_id INT DEFAULT NULL, menu_id INT DEFAULT NULL, nombre VARCHAR(255) NOT NULL, nivel INT NOT NULL, orden INT NOT NULL, visible TINYINT(1) NOT NULL, tiene_hijos TINYINT(1) NOT NULL, accion LONGTEXT DEFAULT NULL, externo TINYINT(1) NOT NULL, INDEX IDX_5A0A972C9AB44FE0 (menu_item_id), INDEX IDX_5A0A972CCCD7E912 (menu_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lebenlabs_simplecms_menus (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE lebenlabs_simplecms_menu_items ADD CONSTRAINT FK_5A0A972C9AB44FE0 FOREIGN KEY (menu_item_id) REFERENCES lebenlabs_simplecms_menu_items (id)');
        $this->addSql('ALTER TABLE lebenlabs_simplecms_menu_items ADD CONSTRAINT FK_5A0A972CCCD7E912 FOREIGN KEY (menu_id) REFERENCES lebenlabs_simplecms_menus (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE lebenlabs_simplecms_menu_items DROP FOREIGN KEY FK_5A0A972C9AB44FE0');
        $this->addSql('ALTER TABLE lebenlabs_simplecms_menu_items DROP FOREIGN KEY FK_5A0A972CCCD7E912');
        $this->addSql('DROP TABLE lebenlabs_simplecms_menu_items');
        $this->addSql('DROP TABLE lebenlabs_simplecms_menus');
    }
}
