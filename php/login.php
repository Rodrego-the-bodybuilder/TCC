<?php
session_start();
include("../conexao.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Escapando entradas do usuário
    $email = $conexao->real_escape_string($_POST['email']);
    $senha = $_POST['senha'];

    // Verifica se a conexão foi estabelecida corretamente
    if ($conexao->connect_error) {
        die("Erro de conexão: " . $conexao->connect_error);
    }

    // Prepara a consulta para o banco de dados
    $stmt = $conexao->prepare("SELECT id, nome, senha, is_admin FROM usuarios WHERE email = ?");

    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            $usuario = $resultado->fetch_assoc();

            // Verifica a senha
            if (password_verify($senha, $usuario['senha'])) {
                // Define o ID do usuário na sessão como usuario_id
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['nome'] = $usuario['nome'];

                // Verifica se o usuário é administrador
                if ($usuario['is_admin'] == 1) {
                    $_SESSION['admin'] = true;
                    header("Location: ../index.php");
                    exit;
                } else {
                    $_SESSION['admin'] = false;
                    header("Location: ../index.php");
                    exit;
                }
            } else {
                $erro = "Senha incorreta.";
            }
        } else {
            $erro = "Usuário não encontrado.";
        }
        $stmt->close();
    } else {
        $erro = "Erro na consulta ao banco de dados: " . $conexao->error;
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
<nav class="bg-yellow-500 p-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-white text-2xl font-bold">Loja Biscuit</h1>
            <form action="index.php" method="GET" class="flex items-center space-x-2">
                <input type="text" name="search" placeholder="Buscar produtos..." class="px-4 py-2 rounded-md">
                <button type="submit" class="bg-white text-yellow-500 px-4 py-2 rounded-md">Buscar</button>
            </form>
            <!-- Menu de navegação -->
            <ul class="hidden md:flex space-x-4 text-white" id="menu">
                <li><a href="index.php" class="hover:text-gray-300">Produtos</a></li>
                <li><a href="#about" class="hover:text-gray-300">Sobre Nós</a></li>
                <li><a href="#contact" class="hover:text-gray-300">Contato</a></li>
                <?php if (isset($_SESSION['nome'])): ?>
                    <li><a href="php/perfil.php" class="hover:text-gray-300">Meu Perfil</a></li>
                    <li><a href="php/carrinho/carrinho.php" class="hover:text-gray-300">Carrinho</a></li>
                    <?php if ($isAdmin): ?>
                        <li><a href="admin/read.php" class="hover:text-gray-300">Admin</a></li>
                    <?php endif; ?>
                <?php else: ?>
                    <li><a href="php/login.php" class="hover:text-gray-300">Login</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <div class="container mx-auto mt-10 max-w-md">
        <h1 class="text-2xl font-bold text-center mb-6">Login</h1>

        <?php if (isset($erro)): ?>
            <p class="text-red-500 mb-4"><?php echo $erro; ?></p>
        <?php endif; ?>

        <form method="POST" action="login.php" class="bg-white p-6 rounded-lg shadow">
            <input type="email" name="email" placeholder="Email" class="block w-full p-2 border rounded mb-4" required>
            <input type="password" name="senha" placeholder="Senha" class="block w-full p-2 border rounded mb-4"
                required>
            <button type="submit" class="w-full bg-yellow-500 p-4 text-white p-2 rounded">Login</button>
        </form>

        <p class="mt-4 text-center">
            Não tem uma conta? <a href="register.php" class="text-blue-500">Registre-se</a>
        </p>
    </div>
</body>

</html>