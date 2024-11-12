<?php
session_start();
include("conexao.php"); // Conexão com o banco de dados

// Verifica se o usuário está logado e se é admin
$isAdmin = isset($_SESSION['admin']) && $_SESSION['admin'] === true;

// Verifica se há uma pesquisa
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';

// Recupera os filtros de preço e avaliação
$minPrice = isset($_GET['preco-min']) && $_GET['preco-min'] !== '' ? (float) $_GET['preco-min'] : 0;
$maxPrice = isset($_GET['preco-max']) && $_GET['preco-max'] !== '' ? (float) $_GET['preco-max'] : 10000;
$avaliacao = isset($_GET['avaliacao']) && $_GET['avaliacao'] !== 'todos' ? (int) $_GET['avaliacao'] : 'todos';

// Inicializa a consulta
$query = "SELECT * FROM produtos WHERE preco BETWEEN $minPrice AND $maxPrice";

// Aplica o filtro de avaliação se selecionado
if ($avaliacao !== 'todos') {
    $query .= " AND avaliacao >= $avaliacao";
}

// Aplica a pesquisa de nome se houver
if ($searchQuery) {
    $searchQuery = $conexao->real_escape_string($searchQuery);
    $query .= " AND nome LIKE '%$searchQuery%'";
}

// Verifica o tipo de ordenação (menor ou maior preço)
$order = isset($_GET['order']) ? $_GET['order'] : '';
if ($order === 'desc') {
    $query .= " ORDER BY preco DESC";
} else {
    $query .= " ORDER BY preco ASC";
}



// Executa a consulta
$result = $conexao->query($query);

// Verifica se há erros na consulta
if (!$result) {
    die("Erro na consulta: " . $conexao->error);
}
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
    
    <!-- Barra Lateral de Filtro -->
    <section class="container mx-auto p-6 flex">
        <div class="w-1/4 p-4 bg-white shadow-md rounded-lg mr-6">
            <h3 class="font-semibold mb-4">Filtrar Produtos</h3>
            <form method="GET" action="" class="space-y-4">
                <!-- Filtro de ordenação -->
                <div>
                    <label class="block font-semibold">Ordenar por:</label>
                    <select name="order" class="w-full p-2 border rounded">
                        <option value="">Selecione...</option>
                        <option value="asc" <?= $order === 'asc' ? 'selected' : '' ?>>Menor preço</option>
                        <option value="desc" <?= $order === 'desc' ? 'selected' : '' ?>>Maior preço</option>
                    </select>
                </div>


                <!-- Botão de aplicar filtros -->
                <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded">Aplicar Filtros</button>
            </form>
        </div>

        <!-- Produtos -->
        <!-- Produtos -->
<div class="flex-1">
    <h2 class="text-3xl font-bold mb-4">Produtos</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <?php
        while ($produto = $result->fetch_assoc()) {
            echo "
            <div class='bg-white p-4 rounded-lg shadow'>
                <a href='php/produto/produto.php?id=" . htmlspecialchars($produto['id']) . "' class='block'>
                    <img src='php/exibir_imagem.php?id=" . htmlspecialchars($produto['id']) . "' alt='" . htmlspecialchars($produto['nome']) . "' class='w-full h-64 object-cover rounded-t-lg'>
                    <h3 class='text-xl font-semibold mt-4 line-clamp-1'>" . htmlspecialchars($produto['nome']) . " - R$ " . htmlspecialchars($produto['preco']) . "</h3>
                </a>
                <form method='POST' action='php/carrinho/add_carrinho.php' class='mt-4'>
                    <input type='hidden' name='produto_id' value='" . htmlspecialchars($produto['id']) . "'>
                    <div class='flex items-center space-x-2'>
                        <label for='quantidade_" . htmlspecialchars($produto['id']) . "' class='text-sm'>Quantidade:</label>
                        <input type='number' id='quantidade_" . htmlspecialchars($produto['id']) . "' name='quantidade' value='1' min='1' class='w-16 px-2 py-1 border border-gray-300 rounded'>
                    </div>
                    <button type='submit' class='bg-yellow-500 text-white px-4 py-2 mt-2 rounded'>Adicionar ao Carrinho</button>
                </form>
            </div>";
        }
        ?>
    </div>
</div>

    </section>
    <!-- Footer -->
    <footer class="bg-yellow-500 text-white py-6">
        <div class="container mx-auto px-4">
            <!-- Divisão em 3 colunas -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Links úteis -->
                <div>
                    <h3 class="text-lg font-semibold mb-2">Links Úteis</h3>
                    <ul class="text-sm text-white-400 space-y-2">
                        <li><a href="#about" class="hover:text-yellow-400">Sobre Nós</a></li>
                        <li><a href="#contact" class="hover:text-yellow-400">Contato</a></li>
                    </ul>
                </div>

                <!-- Redes sociais -->
                <div>
                    <h3 class="text-lg font-semibold mb-2">Siga-nos</h3>
                    <div class="flex space-x-4">
                        <a href="https://facebook.com" target="_blank" class="text-white-400 hover:text-yellow-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor"
                                viewBox="0 0 24 24">
                                <path
                                    d="M22.675 0H1.325C.594 0 0 .593 0 1.326v21.348C0 23.406.594 24 1.325 24H12v-9.294H9.294V11.29H12V8.906c0-2.67 1.631-4.125 4.008-4.125 1.138 0 2.117.084 2.403.122v2.785l-1.651.001c-1.295 0-1.547.616-1.547 1.518v1.992h3.094l-.403 3.416H15.213V24h6.462c.73 0 1.325-.594 1.325-1.326V1.326C24 .594 23.406 0 22.675 0z" />
                            </svg>
                        </a>
                        <a href="https://instagram.com" target="_blank" class="text-white-400 hover:text-yellow-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor"
                                viewBox="0 0 24 24">
                                <path
                                    d="M12 2.163c3.204 0 3.584.012 4.849.07 1.366.062 2.633.334 3.608 1.31.975.975 1.247 2.242 1.309 3.608.058 1.265.07 1.645.07 4.849s-.012 3.584-.07 4.849c-.062 1.366-.334 2.633-1.31 3.608-.975.975-2.242 1.247-3.608 1.309-1.265.058-1.645.07-4.849.07s-3.584-.012-4.849-.07c-1.366-.062-2.633-.334-3.608-1.31-.975-.975-1.247-2.242-1.309-3.608C2.175 15.584 2.163 15.204 2.163 12s.012-3.584.07-4.849c.062-1.366.334-2.633 1.31-3.608.975-.975 2.242-1.247 3.608-1.309C8.416 2.175 8.796 2.163 12 2.163m0-2.163C8.741 0 8.332.014 7.052.072 5.773.129 4.547.444 3.514 1.477 2.481 2.51 2.166 3.736 2.109 5.016.014 8.741 0 8.332 0 12c0 3.259.014 3.668.072 4.948.057 1.28.372 2.506 1.405 3.539.975.975 2.242 1.247 3.608 1.309 3.608 1.265-.058 1.645-.07 4.849-.07s3.584.012 4.849.07c1.366.062 2.633.334 3.608 1.31.975.975 1.247 2.242 1.31 3.608-.058 1.265-.07 1.645-.07 4.849s.012 3.584.07 4.849c-.062 1.366-.334 2.633-1.309 3.608-.975.975-2.242 1.247-3.608 1.309-1.265.058-1.645.07-4.849.07z" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <div class="text-center text-sm text-white-400 mt-8">
                <p>&copy; 2024 Loja Biscuit. Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>
</body>

</html>