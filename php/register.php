<?php
include 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
    $role = 'cliente'; // Todos usuários são clientes por padrão

    $sql = "INSERT INTO usuarios (nome, email, senha, role) VALUES ('$nome', '$email', '$senha', '$role')";
    
    if ($conn->query($sql) === TRUE) {
        echo "Cadastro realizado com sucesso";
        header("Location: login.php");
    } else {
        echo "Erro: " . $sql . "<br>" . $conn->error;
    }
}
?>
<!-- Formulário de cadastro -->
<form action="" method="POST">
    <input type="text" name="nome" placeholder="Nome">
    <input type="email" name="email" placeholder="Email">
    <input type="password" name="senha" placeholder="Senha">
    <button type="submit">Cadastrar</button>
</form>
