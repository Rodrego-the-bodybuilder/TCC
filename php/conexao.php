<?php
$servidor = "localhost";
$usuario = "root";
$senha = "";

// Criando conexão
$conexao = new mysqli($servidor, $usuario, $senha);

// Verificando a conexão
if ($conexao->connect_error) {
    die("Conexão falhou: " . $conexao->connect_error);
}

// Criando a database e selecionando ela
$conexao->query("CREATE DATABASE IF NOT EXISTS bancolojarodrigo");
$conexao->select_db("bancolojarodrigo");

// Criando a tabela de produtos
$conexao->query("
    CREATE TABLE IF NOT EXISTS produtos (
        id INT PRIMARY KEY AUTO_INCREMENT,
        nome VARCHAR(100) NOT NULL,
        descricao TEXT NOT NULL,
        preco DECIMAL(10, 2) NOT NULL
    )
");

$conexao->query("
    CREATE TABLE IF NOT EXISTS usuarios (
        id INT PRIMARY KEY AUTO_INCREMENT,
        nome VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL,
        senha VARCHAR(20) NOT NULL
        )");
?>
