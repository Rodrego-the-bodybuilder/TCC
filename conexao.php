<?php
$servidor = "localhost";
$usuario = "root";
$senha = "";

// Criando a conexão
$conexao = new mysqli($servidor, $usuario, $senha);

// Verificando a conexão
if ($conexao->connect_error) {
    die("Conexão falhou: " . $conexao->connect_error);
}

// Criando o banco de dados e selecionando ele
$conexao->query("CREATE DATABASE IF NOT EXISTS bancolojarodrigo");
$conexao->select_db("bancolojarodrigo");

// Criando a tabela de usuários, caso não exista
$conexao->query("
    CREATE TABLE IF NOT EXISTS usuarios (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL
    )
");

// Dados do admin
$nome = "Rodrigo";
$email = "albuquerque.rodrigo2007@gmail.com";
$senha = password_hash("Arte@1", PASSWORD_DEFAULT); 

// Verifica se o email já existe
$result = $conexao->query("SELECT * FROM usuarios WHERE email = '$email'");

if ($result->num_rows > 0) {
    echo  "<script>alert('Erro!! email ja cadastrado!);</script>";
} else {
    // Insere o usuário se o email não existir
    $sql = "INSERT INTO usuarios (nome, email, senha) VALUES ('$nome', '$email', '$senha')";
    
    if ($conexao->query($sql) === TRUE) {
        echo  "<script>alert('Admin cadastrado com Sucesso!);</script>";
    } else {
        echo "Erro ao adicionar admin: " . $conexao->error;
    }
}

// Criando a tabela de produtos, caso não exista
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
