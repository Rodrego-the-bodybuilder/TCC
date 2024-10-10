<?php
session_start();
include("../conexao.php"); // Inclui a conexão com o banco de dados

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $conexao->real_escape_string($_POST['nome']);
    $email = $conexao->real_escape_string($_POST['email']);
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT); // Hash da senha

    // Verifica se o email já está registrado
    $checkEmail = "SELECT * FROM usuarios WHERE email = '$email'";
    $result = $conexao->query($checkEmail);

    if ($result->num_rows > 0) {
        $error_message = "E-mail já cadastrado!";
    } else {
        // Insere o novo usuário no banco de dados
        $query = "INSERT INTO usuarios (nome, email, senha) VALUES ('$nome', '$email', '$senha')";
        if ($conexao->query($query)) {
            // Registro bem-sucedido, redireciona para login
            header("Location: login.php");
            exit();
        } else {
            $error_message = "Erro ao registrar. Tente novamente.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Registrar</title>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-10 max-w-md">
        <h1 class="text-2xl font-bold text-center mb-6">Registrar</h1>

        <?php if (isset($error_message)): ?>
            <p class="text-red-500 mb-4"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <form method="POST" action="register.php" class="bg-white p-6 rounded-lg shadow">
            <input type="text" name="nome" placeholder="Nome" class="block w-full p-2 border rounded mb-4" required>
            <input type="email" name="email" placeholder="Email" class="block w-full p-2 border rounded mb-4" required>
            <input type="password" name="senha" placeholder="Senha" class="block w-full p-2 border rounded mb-4" required>
            <button type="submit" class="w-full bg-blue-600 text-white p-2 rounded">Registrar</button>
        </form>

        <p class="mt-4 text-center">
            Já tem uma conta? <a href="login.php" class="text-blue-500">Faça login</a>
        </p>
    </div>
</body>
</html>
