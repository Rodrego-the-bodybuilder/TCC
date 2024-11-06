<?php
session_start();
include("../conexao.php");

// Verifica se o usuário está logado e é admin
$isAdmin = isset($_SESSION['admin']) && $_SESSION['admin'] === true;

// Se o usuário não for admin, redireciona para a página inicial
if (!$isAdmin) {
    header("Location: ../index.php");
    exit;
}

$resultado = $conexao->query("SELECT * FROM produtos");
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Lista de Produtos</title>
    <style>
        /* Estilização para truncar a descrição com reticências no final */
        .truncate-text {
            display: -webkit-box;
            -webkit-line-clamp: 4;
            /* Limita a 4 linhas */
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: normal;
        }
    </style>
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
                    <li><a href="../php/perfil.php" class="hover:text-gray-300">Meu Perfil</a></li>
                    <li><a href="../php/carrinho/carrinho.php" class="hover:text-gray-300">Carrinho</a></li>
                    <?php if ($isAdmin): ?>
                        <li><a href="read.php" class="hover:text-gray-300">Admin</a></li>
                    <?php endif; ?>
                <?php else: ?>
                    <li><a href="../php/login.php" class="hover:text-gray-300">Login</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
    <div class="container mx-auto p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-4xl font-bold text-gray-700">Lista de Produtos</h2>
            <a href="create.php" class="bg-yellow-400 text-white px-4 py-2 rounded hover:bg-blue-500">Adicionar Produto</a>
        </div>

        <div class="grid gap-6 lg:grid-cols-3 sm:grid-cols-2">
            <?php while ($produto = $resultado->fetch_assoc()): ?>
                <div class="bg-white rounded-lg shadow-md p-6 flex flex-col justify-between h-full">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-800"><?php echo htmlspecialchars($produto['nome']); ?></h3>
                        <!-- Limita a descrição com CSS para 4 linhas e mostra reticências -->
                        <p class="text-gray-600 mt-2 truncate-text"><?php echo htmlspecialchars($produto['descricao']); ?></p>
                        <p class="text-gray-700 mt-2 font-semibold">R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></p>
                    </div>
                    <div class="mt-6 flex justify-between items-center border-t pt-4">
                        <a href="update.php?id=<?php echo $produto['id']; ?>" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-yellow-500">Atualizar</a>
                        <a href="delete.php?id=<?php echo $produto['id']; ?>" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600" onclick="return confirm('Tem certeza que deseja deletar?');">Deletar</a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>

</html>
