-- Criar o banco de dados
CREATE DATABASE controle_demandas;

-- Usar o banco de dados
USE controle_demandas;

-- Criar tabela de demandas
CREATE TABLE demandas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    criador VARCHAR(255) NOT NULL,
    data_criacao DATE NOT NULL,
    status VARCHAR(50) NOT NULL,
    responsavel_atual VARCHAR(255) NOT NULL
);

-- Criar tabela para o histórico de demandas
CREATE TABLE historico_demandas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    demanda_id INT,
    data_atualizacao DATE NOT NULL,
    responsavel VARCHAR(255) NOT NULL,
    FOREIGN KEY (demanda_id) REFERENCES demandas(id) ON DELETE CASCADE
);
