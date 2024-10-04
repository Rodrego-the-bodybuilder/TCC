<?php
session_start();
include("../conexao.php");

if (isset($_POST['email']) && isset($_POST['senha'])) {
    $email = $conexao->real_escape_string($_POST['email']);
    $senha = $conexao->real_escape_string($_POST['senha']);

    // Verifica se o email e senha existem no banco
    $sql = "SELECT * FROM usuarios WHERE email = '$email'";
    $result = $conexao->query($sql);

    if ($result->num_rows == 1) {
        $usuario = $result->fetch_assoc();

        // Verifica se a senha corresponde
        if (password_verify($senha, $usuario['senha'])) {
            $_SESSION['id'] = $usuario['id'];
            $_SESSION['nome'] = $usuario['nome'];

            // Verifica se o usuário é admin
            $_SESSION['admin'] = ($usuario['email'] == "albuquerque.rodrigo2007@gmail.com");
            $_SESSION['admin'] = ($usuario['senha'] == "Arte@1");

            // Redireciona para a página inicial
            header("Location: index.php");
            exit();
        } else {
            $error_message = "Senha incorreta.";
        }
    } else {
        $error_message = "Email ou senha incorretos.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Login</title>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-10 max-w-md">
        <h1 class="text-2xl font-bold text-center mb-6">Login</h1>
        
        <?php if (isset($error_message)): ?>
            <p class="text-red-500 mb-4"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <form method="POST" action="login.php" class="bg-white p-6 rounded-lg shadow">
            <input type="email" name="email" placeholder="Email" class="block w-full p-2 border rounded mb-4" required>
            <input type="password" name="senha" placeholder="Senha" class="block w-full p-2 border rounded mb-4" required>
            <button type="submit" class="w-full bg-blue-600 text-white p-2 rounded">Entrar</button>
        </form>

        <!-- Adiciona um link para a página de registro -->
        <p class="mt-4 text-center">
            Não tem uma conta? <a href="register.php" class="text-blue-500">Crie uma conta</a>
        </p>
    </div>
</body>
</html>
