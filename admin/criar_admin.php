<?php
include("conexao.php");

// ... (sua conexão com o banco de dados)

// Dados do usuário a ser inserido
$nome = "Admin";
$email = "albuquerque.rodrigo2007@gmail.com";
$senha = password_hash("Arte@1", PASSWORD_DEFAULT);

// Preparar a consulta para verificar se o email já existe
$stmt = $conexao->prepare("SELECT id FROM usuarios WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

// Se o email não existir, inserir o novo usuário
if ($result->num_rows === 0) {
    $stmt->close(); // Fechar a consulta anterior

    // Preparar a consulta de inserção
    $stmt = $conexao->prepare("INSERT INTO usuarios (nome, email, senha, is_admin) VALUES (?, ?, ?, 1)");
    $stmt->bind_param("sss", $nome, $email, $senha);

    if ($stmt->execute()) {
        echo "Usuário administrador cadastrado com sucesso!";
    } else {
        // Logar o erro para depuração
        error_log("Erro ao adicionar admin: " . $stmt->error);
        echo "Ocorreu um erro ao cadastrar o usuário.";
    }
} else {
    echo "O email já está cadastrado.";
}

$stmt->close();
$conexao->close(); // Fechar a conexão com o banco de dados
?>