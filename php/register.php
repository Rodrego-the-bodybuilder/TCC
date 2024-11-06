<?php
session_start();
include("../conexao.php"); // Inclui a conexão com o banco de dados

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $conexao->real_escape_string($_POST['nome']);
    $email = $conexao->real_escape_string($_POST['email']);
    $cpf = $conexao->real_escape_string($_POST['cpf']);
    $data_nascimento = $conexao->real_escape_string($_POST['data_nascimento']);
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT); // Hash da senha

    // Verificação de formato do CPF
    if (strlen($cpf) != 11 || !ctype_digit($cpf)) {
        $error_message = "CPF inválido. Deve conter exatamente 11 dígitos numéricos.";
    } else {
        // Verifica se o email ou CPF já está registrado
        $checkUser = "SELECT * FROM usuarios WHERE email = '$email' OR cpf = '$cpf'";
        $result = $conexao->query($checkUser);

        if ($result->num_rows > 0) {
            $error_message = "E-mail ou CPF já cadastrado!";
        } else {
            // Insere o novo usuário no banco de dados
            $query = "INSERT INTO usuarios (nome, email, cpf, data_nascimento, senha) VALUES ('$nome', '$email', '$cpf', '$data_nascimento', '$senha')";
            if ($conexao->query($query)) {
                header("Location: login.php");
                exit();
            } else {
                $error_message = "Erro ao registrar. Tente novamente.";
            }
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
    <nav class="bg-yellow-500 p-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-white text-2xl font-bold">Loja Biscuit</h1>
            <button class="md:hidden text-white" id="menu-toggle">
                <!-- Ícone do menu hamburguer -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                </svg>
            </button>
            <ul class="hidden md:flex space-x-4 text-white" id="menu">
                <li><a href="../index.php" class="hover:text-gray-300">Produtos</a></li>
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
        <h1 class="text-2xl font-bold text-center mb-6">Registrar</h1>

        <?php if (isset($error_message)): ?>
            <p class="text-red-500 mb-4"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <form method="POST" action="register.php" class="bg-white p-6 rounded-lg shadow">
            <input type="text" name="nome" placeholder="Nome" class="block w-full p-2 border rounded mb-4" required>
            <input type="email" name="email" placeholder="Email" class="block w-full p-2 border rounded mb-4" required>
            <input type="text" name="cpf" placeholder="CPF" maxlength="11" class="block w-full p-2 border rounded mb-4"
                required>
            <input type="date" name="data_nascimento" placeholder="Data de Nascimento"
                class="block w-full p-2 border rounded mb-4" required>
            <input type="password" name="senha" placeholder="Senha" class="block w-full p-2 border rounded mb-4"
                required>
            <button type="submit" class="w-full bg-blue-600 text-white p-2 rounded">Registrar</button>
        </form>

        <p class="mt-4 text-center">
            Já tem uma conta? <a href="login.php" class="text-blue-500">Faça login</a>
        </p>
    </div>
</body>

</html>