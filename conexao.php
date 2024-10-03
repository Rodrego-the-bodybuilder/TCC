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

// Dados do admin
$nome = "Rodrigo";
$email = "albuquerque.rodrigo2007@gmail.com";
$senha = password_hash("Arte@1", PASSWORD_DEFAULT); 

// Inserindo admin no banco de dados
$sql = "INSERT INTO usuarios (nome, email, senha) VALUES ('$nome', '$email', '$senha')";

if ($conexao->query($sql) === TRUE) {
    echo "ADMINISTRADOR";
} else {
    echo "Erro ao adicionar admin: " . $conexao->error;
}



// Criando a tabela de produtos
$conexao->query("
    CREATE TABLE IF NOT EXISTS usuarios (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL
    )
");


$conexao->query("
    CREATE TABLE IF NOT EXISTS produtos (
        id INT PRIMARY KEY AUTO_INCREMENT,
        nome VARCHAR(100) NOT NULL,
        descricao TEXT NOT NULL,
        preco DECIMAL(10, 2) NOT NULL,
        imagem VARCHAR(255) DEFAULT NULL
    )
");

?>
