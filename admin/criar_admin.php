<?php
include("conexao.php");

$conexao = new mysqli("localhost", "root", "", "bancolojarodrigo");

if ($conexao->connect_error) {
    die("Erro de conexão: " . $conexao->connect_error);
}

$nome = "Rodrigo";
$email = "albuquerque.rodrigo2007@gmail.com";
$senha = password_hash("Arte@1", PASSWORD_DEFAULT);

$stmt = $conexao->prepare("SELECT * FROM usuarios WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "<script>alert('Erro! Email já cadastrado!');</script>";
} else {
    $stmt = $conexao->prepare("INSERT INTO usuarios (nome, email, senha, is_admin) VALUES (?, ?, ?, 1)");
    $stmt->bind_param("sss", $nome, $email, $senha);

    if ($stmt->execute()) {
        echo "<script>alert('Admin cadastrado com sucesso!');</script>";
    } else {
        echo "Erro ao adicionar admin: " . $conexao->error;
    }
}

$stmt->close();
$conexao->close();
?>
