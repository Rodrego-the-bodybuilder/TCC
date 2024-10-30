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
if (!$conexao->query("
    CREATE TABLE IF NOT EXISTS usuarios (
        id INT PRIMARY KEY AUTO_INCREMENT,
        nome VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        senha VARCHAR(255) NOT NULL,
        is_admin TINYINT(1) DEFAULT 0
    )
")) {
    echo "Erro ao criar tabela 'usuarios': " . $conexao->error;
}

// Criando a tabela de produtos
if (!$conexao->query("
    CREATE TABLE IF NOT EXISTS produtos (
        id INT PRIMARY KEY AUTO_INCREMENT,
        nome VARCHAR(100) NOT NULL,
        descricao TEXT NOT NULL,
        preco DECIMAL(10, 2) NOT NULL,
        imagem MEDIUMBLOB DEFAULT NULL
    )
")) {
    echo "Erro ao criar tabela 'produtos': " . $conexao->error;
}

// Criando a tabela de pedidos
if (!$conexao->query("
    CREATE TABLE IF NOT EXISTS pedidos (
        id INT PRIMARY KEY AUTO_INCREMENT,
        usuario_id INT NOT NULL,
        data_pedido DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        status VARCHAR(50) NOT NULL DEFAULT 'Pendente',
        total DECIMAL(10, 2) NOT NULL,
        FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
    )
")) {
    echo "Erro ao criar tabela 'pedidos': " . $conexao->error;
}

// Criando a tabela de itens do pedido
if (!$conexao->query("
    CREATE TABLE IF NOT EXISTS itens_pedido (
        id INT PRIMARY KEY AUTO_INCREMENT,
        pedido_id INT NOT NULL,
        produto_id INT NOT NULL,
        quantidade INT NOT NULL,
        preco_unitario DECIMAL(10, 2) NOT NULL,
        FOREIGN KEY (pedido_id) REFERENCES pedidos(id),
        FOREIGN KEY (produto_id) REFERENCES produtos(id)
    )
")) {
    echo "Erro ao criar tabela 'itens_pedido': " . $conexao->error;
}

// Criando a tabela de pagamentos
if (!$conexao->query("
    CREATE TABLE IF NOT EXISTS pagamentos (
        id INT PRIMARY KEY AUTO_INCREMENT,
        pedido_id INT NOT NULL,
        valor DECIMAL(10, 2) NOT NULL,
        metodo_pagamento VARCHAR(50) NOT NULL,
        data_pagamento DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        status_pagamento VARCHAR(50) NOT NULL,
        FOREIGN KEY (pedido_id) REFERENCES pedidos(id)
    )
")) {
    echo "Erro ao criar tabela 'pagamentos': " . $conexao->error;
}

// Criando a tabela de endereços de entrega
if (!$conexao->query("
    CREATE TABLE IF NOT EXISTS enderecos_entrega (
        id INT PRIMARY KEY AUTO_INCREMENT,
        usuario_id INT NOT NULL,
        rua VARCHAR(255) NOT NULL,
        numero VARCHAR(10) NOT NULL,
        complemento VARCHAR(255),
        cidade VARCHAR(100) NOT NULL,
        estado VARCHAR(100) NOT NULL,
        cep VARCHAR(20) NOT NULL,
        FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
    )
")) {
    echo "Erro ao criar tabela 'enderecos_entrega': " . $conexao->error;
}

// Criando a tabela de carrinho
if (!$conexao->query("
    CREATE TABLE IF NOT EXISTS carrinho (
        id INT PRIMARY KEY AUTO_INCREMENT,
        usuario_id INT NOT NULL,
        produto_id INT NOT NULL,
        quantidade INT NOT NULL,
        FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
        FOREIGN KEY (produto_id) REFERENCES produtos(id)
    )
")) {
    echo "Erro ao criar tabela 'carrinho': " . $conexao->error;
}

// Criando a tabela de avaliações
if (!$conexao->query("
    CREATE TABLE IF NOT EXISTS avaliacoes (
        id INT PRIMARY KEY AUTO_INCREMENT,
        produto_id INT NOT NULL,
        usuario_id INT NOT NULL,
        nota INT NOT NULL CHECK (nota >= 1 AND nota <= 5),
        comentario TEXT,
        data_avaliacao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (produto_id) REFERENCES produtos(id),
        FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
    )
")) {
    echo "Erro ao criar tabela 'avaliacoes': " . $conexao->error;
}

// Fechando a conexão

?>
