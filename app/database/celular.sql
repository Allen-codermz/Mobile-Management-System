DROP DATABASE celular;
CREATE DATABASE celular;
USE celular;

CREATE TABLE Continente (
codigoContinente INT AUTO_INCREMENT,
continente VARCHAR(40) NOT NULL,
PRIMARY KEY (codigoContinente)
);

CREATE TABLE Pais (
codigoPais INT AUTO_INCREMENT,
pais VARCHAR(75) NOT NULL,
codigoContinente INT,
PRIMARY KEY (codigoPais),
FOREIGN KEY (codigoContinente)
REFERENCES Continente(codigoContinente)
);

CREATE TABLE fabricante (
codigoFabricante INT AUTO_INCREMENT,
fabricante VARCHAR(30)NOT NULL,
codigoPais INT,
PRIMARY KEY (codigoFabricante),
FOREIGN KEY (codigoPais)
REFERENCES Pais(codigoPais)
);

CREATE TABLE marca (
codigoMarca INT AUTO_INCREMENT,
marca VARCHAR(30) NOT NULL,
codigoFabricante INT,
PRIMARY KEY (codigoMarca),
FOREIGN KEY (codigoFabricante)
REFERENCES fabricante(codigoFabricante)
);

CREATE TABLE modelo (
    codigoModelo INT AUTO_INCREMENT,
    modelo VARCHAR(30) NOT NULL,
    codigoMarca INT,
    PRIMARY KEY (codigoModelo),
    FOREIGN KEY (codigoMarca)
        REFERENCES marca(codigoMarca)
);

CREATE TABLE cor (
codigoCor INT AUTO_INCREMENT,
Cor VARCHAR(7) NOT NULL,
descricao VARCHAR(100) NOT NULL,
PRIMARY KEY (codigoCor)
);

CREATE TABLE celulares (
numeroDeSerie INT AUTO_INCREMENT,
preco DECIMAL(10,2) NOT NULL,
anoDeFabrico YEAR NOT NULL,
codigoMarca INT,
codigoFabricante INT,
codigoCor INT,
codigoCelular INT,
codigoModelo INT,
PRIMARY KEY (numeroDeSerie),
FOREIGN KEY (codigoCor) REFERENCES cor(codigoCor),
FOREIGN KEY (codigoMarca) REFERENCES marca(codigoMarca),
FOREIGN KEY (codigoModelo) REFERENCES modelo(codigoModelo),
FOREIGN KEY (codigoFabricante) REFERENCES fabricante(codigoFabricante)
);

CREATE TABLE usuario(
codigoUsuario INT AUTO_INCREMENT,
nome VARCHAR (30) NOT NULL,
apelido VARCHAR (30) NOT NULL,
email VARCHAR(50) NOT NULL,
contacto INT NOT NULL,
sexo VARCHAR(1) NOT NULL,
estado_civil VARCHAR(15) NOT NULL,
PRIMARY KEY (codigoUsuario)
);

