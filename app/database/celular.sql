-- ============================================
-- Base de dados: celular
-- ============================================

DROP DATABASE IF EXISTS celular;
CREATE DATABASE celular;
USE celular;

-- --------------------------------------------
-- Continente
-- --------------------------------------------
CREATE TABLE Continente (
    codigoContinente INT NOT NULL AUTO_INCREMENT,
    continente VARCHAR(40) NOT NULL,
    PRIMARY KEY (codigoContinente)
);

-- --------------------------------------------
-- Pais
-- --------------------------------------------
CREATE TABLE Pais (
    codigoPais INT NOT NULL AUTO_INCREMENT,
    pais VARCHAR(75) NOT NULL,
    codigoContinente INT DEFAULT NULL,
    PRIMARY KEY (codigoPais),
    FOREIGN KEY (codigoContinente) REFERENCES Continente (codigoContinente)
);

-- --------------------------------------------
-- fabricante
-- --------------------------------------------
CREATE TABLE fabricante (
    codigoFabricante INT NOT NULL AUTO_INCREMENT,
    fabricante VARCHAR(30) NOT NULL,
    codigoPais INT NOT NULL,
    PRIMARY KEY (codigoFabricante),
    FOREIGN KEY (codigoPais) REFERENCES Pais (codigoPais)
);

-- --------------------------------------------
-- marca
-- --------------------------------------------
CREATE TABLE marca (
    codigoMarca INT NOT NULL AUTO_INCREMENT,
    marca VARCHAR(30) NOT NULL,
    codigoFabricante INT NOT NULL,
    PRIMARY KEY (codigoMarca),
    FOREIGN KEY (codigoFabricante) REFERENCES fabricante (codigoFabricante)
);

-- --------------------------------------------
-- modelo
-- --------------------------------------------
CREATE TABLE modelo (
    codigoModelo INT NOT NULL AUTO_INCREMENT,
    modelo VARCHAR(30) DEFAULT NULL,
    codigoMarca INT DEFAULT NULL,
    PRIMARY KEY (codigoModelo),
    FOREIGN KEY (codigoMarca) REFERENCES marca (codigoMarca)
);

-- --------------------------------------------
-- cor
-- --------------------------------------------
CREATE TABLE cor (
    codigoCor INT NOT NULL AUTO_INCREMENT,
    Cor VARCHAR(7) NOT NULL,
    descricao VARCHAR(100) NOT NULL,
    PRIMARY KEY (codigoCor)
);

-- --------------------------------------------
-- celulares
-- --------------------------------------------
CREATE TABLE celulares (
    numeroDeSerie INT NOT NULL AUTO_INCREMENT,
    preco DECIMAL(10,2) NOT NULL,
    anoDeFabrico YEAR NOT NULL,
    codigoMarca INT DEFAULT NULL,
    codigoFabricante INT DEFAULT NULL,
    codigoCor INT DEFAULT NULL,
    codigoModelo INT DEFAULT NULL,
    PRIMARY KEY (numeroDeSerie),
    FOREIGN KEY (codigoCor) REFERENCES cor (codigoCor),
    FOREIGN KEY (codigoMarca) REFERENCES marca (codigoMarca),
    FOREIGN KEY (codigoModelo) REFERENCES modelo (codigoModelo),
    FOREIGN KEY (codigoFabricante) REFERENCES fabricante (codigoFabricante)
);

-- --------------------------------------------
-- perfil
-- --------------------------------------------
CREATE TABLE perfil (
    codigoPerfil INT NOT NULL AUTO_INCREMENT,
    nome VARCHAR(30) DEFAULT NULL,
    PRIMARY KEY (codigoPerfil)
);

-- --------------------------------------------
-- usuario
-- --------------------------------------------
CREATE TABLE usuario (
    codigoUsuario INT NOT NULL AUTO_INCREMENT,
    nome VARCHAR(30) NOT NULL,
    apelido VARCHAR(30) NOT NULL,
    email VARCHAR(50) NOT NULL,
    contacto INT NOT NULL,
    senha VARCHAR(255) NOT NULL,
    codigoPerfil INT DEFAULT NULL,
    username VARCHAR(100) DEFAULT NULL,
    PRIMARY KEY (codigoUsuario),
    FOREIGN KEY (codigoPerfil) REFERENCES perfil (codigoPerfil)
);
