CREATE DATABASE IF NOT EXISTS db_pweb1_bioviagens;
USE db_pweb1_bioviagens;

CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    telefone VARCHAR(20) NOT NULL,
    email VARCHAR(100) NOT NULL,
    login VARCHAR(50) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL
);

INSERT INTO usuarios (nome, telefone, email, login, senha) VALUES 
('Administrador', '0000000000', 'admin@bioviagens.com', 'admin', '123');

CREATE TABLE IF NOT EXISTS destinos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome_cidade VARCHAR(100) NOT NULL,
    pais VARCHAR(100) NOT NULL,
    preco_base DECIMAL(10,2) NOT NULL,
    tipo_voo VARCHAR(50) NOT NULL
);

CREATE TABLE IF NOT EXISTS clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome_completo VARCHAR(150) NOT NULL,
    cpf VARCHAR(14) NOT NULL UNIQUE,
    telefone_contato VARCHAR(20) NOT NULL,
    email_contato VARCHAR(100) NOT NULL
);

CREATE TABLE IF NOT EXISTS reservas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_cliente INT NOT NULL,
    id_destino INT NOT NULL,
    data_viagem DATE NOT NULL,
    status_pagamento VARCHAR(50) NOT NULL,
    FOREIGN KEY (id_cliente) REFERENCES clientes(id),
    FOREIGN KEY (id_destino) REFERENCES destinos(id)
);
