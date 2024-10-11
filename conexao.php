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

// Criando a tabela de usuários com a coluna is_admin
$conexao->query("
    CREATE TABLE IF NOT EXISTS usuarios (
        id INT PRIMARY KEY AUTO_INCREMENT,
        nome VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        senha VARCHAR(255) NOT NULL,
        is_admin TINYINT(1) DEFAULT 0 -- Coluna para definir se o usuário é admin (0 = não, 1 = sim)
    )
");

// Criando a tabela de produtos
$conexao->query("
    CREATE TABLE IF NOT EXISTS produtos (
        id INT PRIMARY KEY AUTO_INCREMENT,
        nome VARCHAR(100) NOT NULL,
        descricao TEXT NOT NULL,
        preco DECIMAL(10, 2) NOT NULL,
        imagem MEDIUMBLOB
    )
");

// Criando um admin padrão, se ele ainda não existir
$nome = "Rodrigo";
$email = "albuquerque.rodrigo2007@gmail.com";
$senha = password_hash("Arte@1", PASSWORD_DEFAULT);

// Verifica se o email já existe
$result = $conexao->query("SELECT * FROM usuarios WHERE email = '$email'");

if ($result->num_rows == 0) {
    // Se o email não existe, insira o novo admin
    if ($conexao->query("INSERT ignore INTO usuarios (nome, email, senha, is_admin) VALUES ('$nome', '$email', '$senha', 1)") === TRUE) {
        echo "Admin cadastrado com sucesso!";
    } else {
        die("Erro ao cadastrar admin: " . $conexao->error);
    }
} 

?>
