<?php
session_start();
include("../conexao.php"); // Conexão com o banco de dados

// Verifica se o usuário está logado
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

// Obtendo os produtos do carrinho para o usuário logado
$user_id = $_SESSION['id'];
$query = "SELECT produtos.nome, produtos.preco, carrinho.quantidade FROM carrinho JOIN produtos ON carrinho.produto_id = produtos.id WHERE carrinho.usuario_id = $user_id";
$result = $conexao->query($query);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Carrinho de Compras</title>
</head>
<body class="bg-gray-100">
    <!-- Navbar (mesma do index.php) -->
    <nav class="bg-blue-600 p-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-white text-2xl font-bold">Loja do Rodrigo</h1>
            <button class="md:hidden text-white" id="menu-toggle">
                <!-- Ícone do menu hamburguer -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                </svg>
            </button>
            <ul class="hidden md:flex space-x-4 text-white" id="menu">
                <li><a href="index.php" class="hover:text-gray-300">Produtos</a></li>
                <li><a href="#about" class="hover:text-gray-300">Sobre Nós</a></li>
                <li><a href="#contact" class="hover:text-gray-300">Contato</a></li>
                <?php if (isset($_SESSION['nome'])): ?>
                    <li><a href="logout.php" class="hover:text-gray-300">Sair</a></li>
                    <li><a href="carrinho.php" class="hover:text-gray-300">Carrinho</a></li>
                <?php else: ?>
                    <li><a href="login.php" class="hover:text-gray-300">Login</a></li>
                <?php endif; ?>
            </ul>
        </div>

        <!-- Script do menu hamburguer -->
        <script>
            document.getElementById("menu-toggle").addEventListener("click", function() {
                var menu = document.getElementById("menu");
                menu.classList.toggle("hidden");
            });
        </script>
    </nav>

    <!-- Carrinho -->
    <section class="container mx-auto p-6">
        <h2 class="text-3xl font-bold mb-4">Seu Carrinho</h2>
        <div class="bg-white p-6 rounded-lg shadow">
            <?php if ($result->num_rows > 0): ?>
                <ul>
                    <?php while ($item = $result->fetch_assoc()): ?>
                        <li class="border-b py-4">
                            <span class="font-semibold"><?php echo $item['nome']; ?></span> - 
                            <span>R$ <?php echo number_format($item['preco'], 2, ',', '.'); ?></span> x 
                            <span><?php echo $item['quantidade']; ?></span>
                        </li>
                    <?php endwhile; ?>
                </ul>
                <button class="bg-green-600 text-white px-4 py-2 mt-4 rounded">Finalizar Compra</button>
            <?php else: ?>
                <p>Seu carrinho está vazio.</p>
            <?php endif; ?>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-blue-600 text-white p-4 mt-6 text-center">
        <p>&copy; 2024 Loja do Rodrigo. Todos os direitos reservados.</p>
    </footer>
</body>
</html>
