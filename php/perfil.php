<?php
session_start();
include("../conexao.php");

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// Obtém as informações do usuário logado
$user_id = $_SESSION['usuario_id'];
$sql = "SELECT * FROM usuarios WHERE id = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();

// Verifica se o usuário é administrador
$isAdmin = $usuario['is_admin'] == 1; // Ajuste para o nome do campo que indica administrador
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

<nav class="bg-yellow-500 p-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-white text-2xl font-bold">Loja Biscuit</h1>
            <form action="../index.php" method="GET" class="flex items-center space-x-2">
                <input type="text" name="search" placeholder="Buscar produtos..." class="px-4 py-2 rounded-md">
                <button type="submit" class="bg-white text-yellow-500 px-4 py-2 rounded-md">
                    <i class="fas fa-search"></i> <!-- Ícone de lupa -->
                </button>
            </form>
            <!-- Menu de navegação -->
            <ul class="hidden md:flex space-x-4 text-white" id="menu">
                <li><a href="../index.php" class="hover:text-gray-300">Produtos</a></li>
                <li><a href="#about" class="hover:text-gray-300">Sobre Nós</a></li>
                <li><a href="#contact" class="hover:text-gray-300">Contato</a></li>
                <?php if (isset($_SESSION['nome'])): ?>
                    <li><a href="perfil.php" class="hover:text-gray-300">Meu Perfil</a></li>
                    <li><a href="carrinho/carrinho.php" class="hover:text-gray-300">Carrinho</a></li>
                    <?php if ($isAdmin): ?>
                        <li><a href="../admin/read.php" class="hover:text-gray-300">Admin</a></li>
                    <?php endif; ?>
                <?php else: ?>
                    <li><a href="/login.php" class="hover:text-gray-300">Login</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <!-- Corpo Principal -->
    <div class="container mx-auto mt-10 max-w-4xl px-4">
        <div class="bg-white shadow-lg rounded-lg p-8 space-y-6">
            <h1 class="text-3xl font-semibold text-center text-gray-800 mb-6">Meu Perfil</h1>
            
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="font-semibold text-gray-600">Nome:</span>
                    <span><?php echo htmlspecialchars($usuario['nome']); ?></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="font-semibold text-gray-600">Email:</span>
                    <span><?php echo htmlspecialchars($usuario['email']); ?></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="font-semibold text-gray-600">CPF:</span>
                    <span><?php echo htmlspecialchars($usuario['cpf']); ?></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="font-semibold text-gray-600">Data de Nascimento:</span>
                    <span><?php echo htmlspecialchars($usuario['data_nascimento']); ?></span>
                </div>
            </div>

            <!-- Botão de Logout -->
            <div class="flex justify-center mt-6">
                <a href="logout.php" class="bg-yellow-500 text-white px-4 py-2 rounded shadow hover:bg-yellow-600">
                    Sair
                </a>
            </div>
        </div>
    </div>

    <!-- Scripts para responsividade -->
    <script>
        const menuToggle = document.getElementById('menu-toggle');
        const menu = document.getElementById('menu');

        menuToggle.addEventListener('click', () => {
            menu.classList.toggle('hidden');
        });
    </script>
</body>

</html>
