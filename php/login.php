<?php
session_start();
include("../conexao.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $stmt = $conexao->prepare("SELECT id, nome, senha, is_admin FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();

        if (password_verify($senha, $usuario['senha'])) {
            $_SESSION['user_id'] = $usuario['id'];
            $_SESSION['nome'] = $usuario['nome'];

            if ($usuario['is_admin'] == 1) {
                $_SESSION['admin'] = true;
                header("Location: ../admin/admin.php");
                exit;
            } else {
                $_SESSION['admin'] = false;
                header("Location: home.php");
                exit;
            }
        } else {
            $erro = "Senha incorreta.";
        }
    } else {
        $erro = "Usuário não encontrado.";
    }
    $stmt->close();
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

        <?php if (isset($erro)): ?>
            <p class="text-red-500 mb-4"><?php echo $erro; ?></p>
        <?php endif; ?>

        <form method="POST" action="login.php" class="bg-white p-6 rounded-lg shadow">
            <input type="email" name="email" placeholder="Email" class="block w-full p-2 border rounded mb-4" required>
            <input type="password" name="senha" placeholder="Senha" class="block w-full p-2 border rounded mb-4" required>
            <button type="submit" class="w-full bg-blue-600 text-white p-2 rounded">Login</button>
        </form>

        <p class="mt-4 text-center">
            Não tem uma conta? <a href="register.php" class="text-blue-500">Registre-se</a>
        </p>
    </div>
</body>
</html>
