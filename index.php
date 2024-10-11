<?php
session_start();
include("conexao.php"); // Conexão com o banco de dados

// Verifica se o usuário está logado e se é admin
$isAdmin = isset($_SESSION['admin']) && $_SESSION['admin'] === true;
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Loja do Artesão</title>
</head>
<body class="bg-gray-100">
    <!-- Navbar -->
    <nav class="bg-yellow-500 p-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-white text-2xl font-bold">Loja Biscuit</h1>
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
                    <li><a href="php/perfil.php" class="hover:text-gray-300">Meu Perfil</a></li>
                    <li><a href="php/logout.php" class="hover:text-gray-300">Sair</a></li>
                    <li><a href="php/carrinho.php" class="hover:text-gray-300">Carrinho</a></li>
                    <?php if ($isAdmin): ?>
                        <li><a href="admin/admin.php" class="hover:text-gray-300">Admin</a></li>
                    <?php endif; ?>
                <?php else: ?>
                    <li><a href="php/login.php" class="hover:text-gray-300">Login</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <!-- Script do menu hamburguer -->
    <script>
        document.getElementById("menu-toggle").addEventListener("click", function() {
            var menu = document.getElementById("menu");
            menu.classList.toggle("hidden");
        });
    </script>

    <!-- Produtos -->
    <section class="container mx-auto p-6">
        <h2 class="text-3xl font-bold mb-4">Produtos</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <?php
            $query = "SELECT * FROM produtos";
            $result = $conexao->query($query);
            while ($produto = $result->fetch_assoc()) {
                echo "
                <div class='bg-white p-4 rounded-lg shadow'>
                    <img src='php/exibir_imagem.php?id=" . htmlspecialchars($produto['id']) . "' alt='" . htmlspecialchars($produto['nome']) . "' class='w-full h-48 object-cover rounded-t-lg'>
                    <h3 class='text-xl font-semibold mt-4'>" . htmlspecialchars($produto['nome']) . " - R$ " . htmlspecialchars($produto['preco']) . "</h3>
                    <p class='mt-2'>" . htmlspecialchars($produto['descricao']) . "</p>
                    <form method='POST' action='adicionar_carrinho.php'>
                        <input type='hidden' name='produto_id' value='" . htmlspecialchars($produto['id']) . "'>
                        <button type='submit' class='bg-yellow-500 text-white px-4 py-2 mt-4 rounded'>Adicionar ao Carrinho</button>
                    </form>
                </div>";
            }            
            ?>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-yellow-500 text-white p-4 mt-6 text-center">
        <p>&copy; 2024 Loja do Rodrigo. Todos os direitos reservados.</p>
    </footer>
</body>
</html>
