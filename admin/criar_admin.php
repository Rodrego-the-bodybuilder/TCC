<?php
include("conexao.php");

// Dados do admin
$nome = "Rodrigo";
$email = "albuquerque.rodrigo2007@gmail.com";
$senha = password_hash("Arte@1", PASSWORD_DEFAULT);  

// Verifica se o email já existe
$result = $conexao->query("SELECT * FROM usuarios WHERE email = '$email'");

if ($result->num_rows > 0) {
    echo "<script>alert('Erro!! email já cadastrado!');</script>";
} else {
    // Insere o usuário se o email não existir
    $sql = "INSERT INTO usuarios (nome, email, senha) VALUES ('$nome', '$email', '$senha')";
    
    if ($conexao->query($sql) === TRUE) {
        echo "<script>alert('Admin cadastrado com sucesso!');</script>";
    } else {
        echo "Erro ao adicionar admin: " . $conexao->error;
    }
}
?>
