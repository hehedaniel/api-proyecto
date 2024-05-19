<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240519160038 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE alimento (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, descripcion LONGTEXT NOT NULL, marca VARCHAR(255) NOT NULL, cantidad DOUBLE PRECISION NOT NULL, proteinas DOUBLE PRECISION NOT NULL, grasas DOUBLE PRECISION NOT NULL, carbohidratos DOUBLE PRECISION NOT NULL, azucares DOUBLE PRECISION NOT NULL, vitaminas DOUBLE PRECISION NOT NULL, minerales DOUBLE PRECISION NOT NULL, imagen LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE consumo_dia (id INT AUTO_INCREMENT NOT NULL, id_usuario_id INT NOT NULL, fecha DATE NOT NULL, comida VARCHAR(255) NOT NULL, cantidad DOUBLE PRECISION NOT NULL, momento VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_77412CD17EB2C349 (id_usuario_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ejercicio (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, descripcion LONGTEXT NOT NULL, grupo_muscular VARCHAR(255) NOT NULL, dificultad VARCHAR(255) NOT NULL, instrucciones LONGTEXT NOT NULL, calorias_quemadas DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE enlace (id INT AUTO_INCREMENT NOT NULL, id_ejercicio_id INT NOT NULL, enlace LONGTEXT NOT NULL, INDEX IDX_8414B27913487F0F (id_ejercicio_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE peso (id INT AUTO_INCREMENT NOT NULL, id_usuario_id INT NOT NULL, fecha DATE NOT NULL, peso DOUBLE PRECISION NOT NULL, imc DOUBLE PRECISION NOT NULL, UNIQUE INDEX UNIQ_DD7820B77EB2C349 (id_usuario_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE receta (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, descripcion LONGTEXT NOT NULL, instrucciones LONGTEXT NOT NULL, cantidad_final DOUBLE PRECISION NOT NULL, proteinas DOUBLE PRECISION NOT NULL, grasas DOUBLE PRECISION NOT NULL, carbohidratos DOUBLE PRECISION NOT NULL, azucares DOUBLE PRECISION NOT NULL, vitaminas DOUBLE PRECISION NOT NULL, minerales DOUBLE PRECISION NOT NULL, imagen LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE usuario (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, rol TINYINT(1) NOT NULL, edad INT NOT NULL, altura DOUBLE PRECISION NOT NULL, peso DOUBLE PRECISION NOT NULL, objetivo_opt VARCHAR(255) NOT NULL, objetivo_num VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE usuario_alimento (usuario_id INT NOT NULL, alimento_id INT NOT NULL, INDEX IDX_4B02670ADB38439E (usuario_id), INDEX IDX_4B02670A974F2E6F (alimento_id), PRIMARY KEY(usuario_id, alimento_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE consumo_dia ADD CONSTRAINT FK_77412CD17EB2C349 FOREIGN KEY (id_usuario_id) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE enlace ADD CONSTRAINT FK_8414B27913487F0F FOREIGN KEY (id_ejercicio_id) REFERENCES ejercicio (id)');
        $this->addSql('ALTER TABLE peso ADD CONSTRAINT FK_DD7820B77EB2C349 FOREIGN KEY (id_usuario_id) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE usuario_alimento ADD CONSTRAINT FK_4B02670ADB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE usuario_alimento ADD CONSTRAINT FK_4B02670A974F2E6F FOREIGN KEY (alimento_id) REFERENCES alimento (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE consumo_dia DROP FOREIGN KEY FK_77412CD17EB2C349');
        $this->addSql('ALTER TABLE enlace DROP FOREIGN KEY FK_8414B27913487F0F');
        $this->addSql('ALTER TABLE peso DROP FOREIGN KEY FK_DD7820B77EB2C349');
        $this->addSql('ALTER TABLE usuario_alimento DROP FOREIGN KEY FK_4B02670ADB38439E');
        $this->addSql('ALTER TABLE usuario_alimento DROP FOREIGN KEY FK_4B02670A974F2E6F');
        $this->addSql('DROP TABLE alimento');
        $this->addSql('DROP TABLE consumo_dia');
        $this->addSql('DROP TABLE ejercicio');
        $this->addSql('DROP TABLE enlace');
        $this->addSql('DROP TABLE peso');
        $this->addSql('DROP TABLE receta');
        $this->addSql('DROP TABLE usuario');
        $this->addSql('DROP TABLE usuario_alimento');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
