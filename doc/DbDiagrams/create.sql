-- MySQL Script generated by MySQL Workbench
-- Thu Dec 28 12:40:56 2023
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema HilfeDb
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `HilfeDb` ;

-- -----------------------------------------------------
-- Schema HilfeDb
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `HilfeDb` DEFAULT CHARACTER SET utf8 ;
USE `HilfeDb` ;

-- -----------------------------------------------------
-- Table `HilfeDb`.`User`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `HilfeDb`.`User` ;

CREATE TABLE IF NOT EXISTS `HilfeDb`.`User` (
  `idUser` INT NOT NULL,
  `Name` VARCHAR(45) NULL,
  `Surname` VARCHAR(45) NULL,
  `PhoneNumber` VARCHAR(45) NULL,
  `Email` VARCHAR(45) NULL,
  `Salt` VARCHAR(100) NULL,
  `Password` VARCHAR(500) NULL,
  `Bio` VARCHAR(45) NULL,
  `Birthday` DATE NULL,
  `PubKey` VARCHAR(45) NULL,
  `FotoProfilo` VARCHAR(100) NULL,
  `Username` VARCHAR(45) NOT NULL,
  `Address` VARCHAR(500) NULL,
  `AddressLat` DECIMAL(14,10) NULL,
  `AddressLong` DECIMAL(14,10) NULL,
  PRIMARY KEY (`idUser`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `HilfeDb`.`PostInterventi`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `HilfeDb`.`PostInterventi` ;

CREATE TABLE IF NOT EXISTS `HilfeDb`.`PostInterventi` (
  `idPostIntervento` INT NOT NULL,
  `TitoloPost` VARCHAR(100) NULL,
  `DescrizionePost` VARCHAR(500) NULL,
  `DataIntervento` DATETIME NULL,
  `DataPubblicazione` DATETIME NULL,
  `PersoneRichieste` INT UNSIGNED NULL,
  `PosizioneLongitudine` DECIMAL(14,10) NULL,
  `PosizioneLatitudine` DECIMAL(14,10) NULL,
  `Indirizzo` VARCHAR(500) NULL,
  `Autore_idUser` INT NOT NULL,
  PRIMARY KEY (`idPostIntervento`),
  INDEX `fk_PostInterventi_User1_idx` (`Autore_idUser` ASC) ,
  CONSTRAINT `fk_PostInterventi_User1`
    FOREIGN KEY (`Autore_idUser`)
    REFERENCES `HilfeDb`.`User` (`idUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `HilfeDb`.`Interventi`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `HilfeDb`.`Interventi` ;

CREATE TABLE IF NOT EXISTS `HilfeDb`.`Interventi` (
  `idPostInterventi` INT NOT NULL,
  `idUser` INT NOT NULL,
  PRIMARY KEY (`idPostInterventi`, `idUser`),
  INDEX `fk_Interventi_PostInterventi1_idx` (`idPostInterventi` ASC) ,
  INDEX `fk_Interventi_User1_idx` (`idUser` ASC) ,
  CONSTRAINT `fk_Interventi_PostInterventi1`
    FOREIGN KEY (`idPostInterventi`)
    REFERENCES `HilfeDb`.`PostInterventi` (`idPostIntervento`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Interventi_User1`
    FOREIGN KEY (`idUser`)
    REFERENCES `HilfeDb`.`User` (`idUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `HilfeDb`.`Materiale`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `HilfeDb`.`Materiale` ;

CREATE TABLE IF NOT EXISTS `HilfeDb`.`Materiale` (
  `idMateriale` INT NOT NULL,
  `DescrizioneMateriale` VARCHAR(200) NULL,
  `Unita` INT UNSIGNED NULL,
  `idPostIntervento` INT NOT NULL,
  PRIMARY KEY (`idMateriale`),
  INDEX `fk_Materiale_PostInterventi1_idx` (`idPostIntervento` ASC) ,
  CONSTRAINT `fk_Materiale_PostInterventi1`
    FOREIGN KEY (`idPostIntervento`)
    REFERENCES `HilfeDb`.`PostInterventi` (`idPostIntervento`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `HilfeDb`.`PostComunicazioni`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `HilfeDb`.`PostComunicazioni` ;

CREATE TABLE IF NOT EXISTS `HilfeDb`.`PostComunicazioni` (
  `idPostComunicazione` INT NOT NULL,
  `idUser` INT NOT NULL,
  `TitoloPost` VARCHAR(100) NULL,
  `DescrizionePost` VARCHAR(500) NULL,
  `Foto` VARCHAR(100) NULL,
  PRIMARY KEY (`idPostComunicazione`),
  CONSTRAINT `fk_PostComunicazioni_User1`
    FOREIGN KEY (`idUser`)
    REFERENCES `HilfeDb`.`User` (`idUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `HilfeDb`.`Seguiti`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `HilfeDb`.`Seguiti` ;

CREATE TABLE IF NOT EXISTS `HilfeDb`.`Seguiti` (
  `idSeguace` INT NOT NULL,
  `idSeguito` INT NOT NULL,
  PRIMARY KEY (`idSeguace`, `idSeguito`),
  INDEX `fk_Seguiti_User2_idx` (`idSeguito` ASC) ,
  CONSTRAINT `fk_Seguiti_User1`
    FOREIGN KEY (`idSeguace`)
    REFERENCES `HilfeDb`.`User` (`idUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Seguiti_User2`
    FOREIGN KEY (`idSeguito`)
    REFERENCES `HilfeDb`.`User` (`idUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `HilfeDb`.`Accessi`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `HilfeDb`.`Accessi` ;

CREATE TABLE IF NOT EXISTS `HilfeDb`.`Accessi` (
  `idUser` INT NOT NULL,
  `tempo` BIGINT(30) NOT NULL,
  PRIMARY KEY (`idUser`, `tempo`),
  CONSTRAINT `fk_Accessi_User1`
    FOREIGN KEY (`idUser`)
    REFERENCES `HilfeDb`.`User` (`idUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `HilfeDb`.`Commento`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `HilfeDb`.`Commento` ;

CREATE TABLE IF NOT EXISTS `HilfeDb`.`Commento` (
  `Autore` INT NOT NULL,
  `RelativoA` INT NOT NULL,
  `Testo` VARCHAR(1000) NULL,
  PRIMARY KEY (`Autore`, `RelativoA`),
  INDEX `fk_Commento_User1_idx` (`Autore` ASC) ,
  INDEX `fk_Commento_PostComunicazioni1_idx` (`RelativoA` ASC) ,
  CONSTRAINT `fk_Commento_User1`
    FOREIGN KEY (`Autore`)
    REFERENCES `HilfeDb`.`User` (`idUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Commento_PostComunicazioni1`
    FOREIGN KEY (`RelativoA`)
    REFERENCES `HilfeDb`.`PostComunicazioni` (`idPostComunicazione`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `HilfeDb`.`PostSalvati`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `HilfeDb`.`PostSalvati` ;

CREATE TABLE IF NOT EXISTS `HilfeDb`.`PostSalvati` (
  `idPostInterventi` INT NOT NULL,
  `idUser` INT NOT NULL,
  PRIMARY KEY (`idPostInterventi`, `idUser`),
  INDEX `fk_Interventi_PostInterventi1_idx` (`idPostInterventi` ASC) ,
  INDEX `fk_Interventi_User1_idx` (`idUser` ASC) ,
  CONSTRAINT `fk_Interventi_PostInterventi10`
    FOREIGN KEY (`idPostInterventi`)
    REFERENCES `HilfeDb`.`PostInterventi` (`idPostIntervento`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Interventi_User10`
    FOREIGN KEY (`idUser`)
    REFERENCES `HilfeDb`.`User` (`idUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `HilfeDb`.`Like`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `HilfeDb`.`Like` ;

CREATE TABLE IF NOT EXISTS `HilfeDb`.`Like` (
  `idEmettitore` INT NOT NULL,
  `PostRelativo` INT NOT NULL,
  PRIMARY KEY (`idEmettitore`, `PostRelativo`),
  INDEX `fk_Like_PostComunicazioni1_idx` (`PostRelativo` ASC) ,
  CONSTRAINT `fk_Like_User1`
    FOREIGN KEY (`idEmettitore`)
    REFERENCES `HilfeDb`.`User` (`idUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Like_PostComunicazioni1`
    FOREIGN KEY (`PostRelativo`)
    REFERENCES `HilfeDb`.`PostComunicazioni` (`idPostComunicazione`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `HilfeDb`.`Notifica`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `HilfeDb`.`Notifica` ;

CREATE TABLE IF NOT EXISTS `HilfeDb`.`Notifica` (
  `idNotifica` INT NOT NULL,
  `idUser` INT NOT NULL,
  `TestoNotifica` VARCHAR(500) NULL,
  `Letta` TINYINT NOT NULL,
  `DataCreazione` DATETIME NOT NULL,
  PRIMARY KEY (`idNotifica`, `idUser`),
  INDEX `fk_Notifica_User1_idx` (`idUser` ASC) ,
  CONSTRAINT `fk_Notifica_User1`
    FOREIGN KEY (`idUser`)
    REFERENCES `HilfeDb`.`User` (`idUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `HilfeDb`.`Token`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `HilfeDb`.`Token` ;

CREATE TABLE IF NOT EXISTS `HilfeDb`.`Token` (
  `idUser` INT NOT NULL,
  `TokenValue` VARCHAR(100) NOT NULL,
  `CreationTime` BIGINT(30) NULL,
  PRIMARY KEY (`idUser`, `TokenValue`),
  INDEX `fk_Token_User1_idx` (`idUser` ASC) ,
  CONSTRAINT `fk_Token_User1`
    FOREIGN KEY (`idUser`)
    REFERENCES `HilfeDb`.`User` (`idUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
