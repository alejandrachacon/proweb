-- MySQL Script generated by MySQL Workbench
-- Tue May 30 13:49:53 2017
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema trycrying
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema trycrying
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `trycrying` DEFAULT CHARACTER SET utf8 ;
USE `trycrying` ;

-- -----------------------------------------------------
-- Table `trycrying`.`usuarios`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `trycrying`.`usuarios` ;

CREATE TABLE IF NOT EXISTS `trycrying`.`usuarios` (
  `usuario` VARCHAR(50) NOT NULL,
  `email` VARCHAR(50) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `rol` VARCHAR(20) NOT NULL,
  PRIMARY KEY (`usuario`),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `trycrying`.`libros`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `trycrying`.`libros` ;

CREATE TABLE IF NOT EXISTS `trycrying`.`libros` (
  `isbn` VARCHAR(60) NOT NULL,
  `titulo` VARCHAR(45) NOT NULL,
  `autor` VARCHAR(45) NOT NULL,
  `editorial` VARCHAR(50) NOT NULL,
  `paginas` INT NOT NULL,
  `disponibles` INT NOT NULL,
  `total` INT NOT NULL,
  `url_imagen` VARCHAR(500) NULL,
  PRIMARY KEY (`isbn`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `trycrying`.`equipos`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `trycrying`.`equipos` ;

CREATE TABLE IF NOT EXISTS `trycrying`.`equipos` (
  `nombre` VARCHAR(45) NOT NULL,
  `serie` INT NOT NULL,
  `fabricante` VARCHAR(45) NOT NULL,
  `disponibles` INT NOT NULL,
  `total` INT NOT NULL,
  `url_imagen` VARCHAR(500) NULL,
  PRIMARY KEY (`nombre`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `trycrying`.`sala`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `trycrying`.`sala` ;

CREATE TABLE IF NOT EXISTS `trycrying`.`sala` (
  `nombre` VARCHAR(45) NOT NULL,
  `disponible` BIT NOT NULL,
  PRIMARY KEY (`nombre`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `trycrying`.`solicitudes`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `trycrying`.`solicitudes` ;

CREATE TABLE IF NOT EXISTS `trycrying`.`solicitudes` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `usuario` VARCHAR(50) NOT NULL,
  `equipos_nombre` VARCHAR(45) NULL,
  `libros_isbn` VARCHAR(60) NULL,
  `sala_nombre` VARCHAR(45) NULL,
  `tipo` VARCHAR(45) NOT NULL,
  `fecha_prestamo` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_vencimiento` DATETIME NULL,
  `reportecada` INT NULL,
  `estado` VARCHAR(20) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_prestamos_usuarios1_idx` (`usuario` ASC),
  INDEX `fk_solicitudes_libros1_idx` (`libros_isbn` ASC),
  INDEX `fk_solicitudes_sala1_idx` (`sala_nombre` ASC),
  INDEX `fk_solicitudes_equipos1_idx` (`equipos_nombre` ASC),
  CONSTRAINT `fk_prestamos_usuarios1`
    FOREIGN KEY (`usuario`)
    REFERENCES `trycrying`.`usuarios` (`usuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_solicitudes_libros1`
    FOREIGN KEY (`libros_isbn`)
    REFERENCES `trycrying`.`libros` (`isbn`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_solicitudes_sala1`
    FOREIGN KEY (`sala_nombre`)
    REFERENCES `trycrying`.`sala` (`nombre`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_solicitudes_equipos1`
    FOREIGN KEY (`equipos_nombre`)
    REFERENCES `trycrying`.`equipos` (`nombre`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `trycrying`.`reportes`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `trycrying`.`reportes` ;

CREATE TABLE IF NOT EXISTS `trycrying`.`reportes` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `solicitudes_id` INT NOT NULL,
  `estado` VARCHAR(45) NOT NULL,
  `comentarios` VARCHAR(255) NULL,
  `fecha_reporte` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `fk_reportes_solicitudes1_idx` (`solicitudes_id` ASC),
  CONSTRAINT `fk_reportes_solicitudes1`
    FOREIGN KEY (`solicitudes_id`)
    REFERENCES `trycrying`.`solicitudes` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `trycrying`.`eventos`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `trycrying`.`eventos` ;

CREATE TABLE IF NOT EXISTS `trycrying`.`eventos` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `fechainicio` DATETIME NOT NULL,
  `fechafin` DATETIME NOT NULL,
  `lugar` VARCHAR(45) NULL,
  `sala_nombre` VARCHAR(45) NULL,
  `nombre` VARCHAR(45) NOT NULL,
  `informacion` VARCHAR(45) NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_eventos_sala1_idx` (`sala_nombre` ASC),
  CONSTRAINT `fk_eventos_sala1`
    FOREIGN KEY (`sala_nombre`)
    REFERENCES `trycrying`.`sala` (`nombre`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;



-- -----------------------------------------------------
-- Table `trycrying`.`suscripciones`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `trycrying`.`suscripciones` ;

CREATE TABLE IF NOT EXISTS `trycrying`.`suscripciones` (
  `usuario` VARCHAR(50) NOT NULL,
  `eventos_id` INT NOT NULL,
  PRIMARY KEY (`usuario`, `eventos_id`),
  INDEX `fk_usuarios_has_eventos_eventos1_idx` (`eventos_id` ASC),
  INDEX `fk_usuarios_has_eventos_usuarios1_idx` (`usuario` ASC),
  CONSTRAINT `fk_usuarios_has_eventos_usuarios1`
    FOREIGN KEY (`usuario`)
    REFERENCES `trycrying`.`usuarios` (`usuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_usuarios_has_eventos_eventos1`
    FOREIGN KEY (`eventos_id`)
    REFERENCES `trycrying`.`eventos` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

/* INSERTS */
-- Usuarios
INSERT INTO `usuarios`(`usuario`, `email`, `password`, `rol`) VALUES ('admin','admin@email.com','Passw0rd#','admin');
INSERT INTO `usuarios`(`usuario`, `email`, `password`, `rol`) VALUES ('test','test@email.com','Passw0rd#','usuario');

-- Equipos
INSERT INTO `equipos`(`nombre`, `fabricante`, `disponibles`, `total`, `url_imagen`, `serie`) VALUES ('XPS','Dell',3,4,'http://i.dell.com/sites/imagecontent/consumer/merchandizing/en/PublishingImages/Franchise-category/xps-family-polaris-sub-cat-franchise-laptops-mod-06.jpg', 1);
INSERT INTO `equipos`(`nombre`, `fabricante`, `disponibles`, `total`, `serie`) VALUES ('Zenbook','Asus',4,4, 2);

-- Salas
INSERT INTO `sala`(`nombre`, `disponible`) VALUES ('Baron',1);
INSERT INTO `sala`(`nombre`, `disponible`) VALUES ('Sala B',1);
INSERT INTO `sala`(`nombre`, `disponible`) VALUES ('Sala A',1);

-- Eventos
INSERT INTO `eventos` (`fechainicio`, `fechafin`, `lugar`, `sala_nombre`, `nombre`, `informacion`) VALUES
('2017-06-03 00:00:00', '2017-06-04 00:00:00', 'Teatro la castellana', NULL, 'GameJam Express', '24h de pura programacion'),
('2017-05-29 00:00:00', '2017-05-30 00:00:00', 'Pablo VI', NULL, 'Eucaristia', 'día de la virgen maria');