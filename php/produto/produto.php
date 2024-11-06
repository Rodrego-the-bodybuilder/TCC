<?php
session_start();
include("../../conexao.php"); // Conexão com o banco de dados

// Verifica se o id do produto foi passado
if (isset($_GET['id'])) {
    $produto_id = $_GET['id'];

    // Query para buscar os detalhes do produto
    $query = "SELECT * FROM produtos WHERE id = ?";
    $stmt = $conexao->prepare($query);
    $stmt->bind_param("i", $produto_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verifica se o produto foi encontrado
    if ($result->num_rows > 0) {
        $produto = $result->fetch_assoc();
    } else {
        $produto = null; // Produto não encontrado
    }

    // Query para buscar avaliações do produto
    $query_avaliacoes = "SELECT * FROM avaliacoes WHERE produto_id = ?";
    $stmt_avaliacoes = $conexao->prepare($query_avaliacoes);
    $stmt_avaliacoes->bind_param("i", $produto_id);
    $stmt_avaliacoes->execute();
    $result_avaliacoes = $stmt_avaliacoes->get_result();
} else {
    $produto = null; // id não fornecido
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Detalhes do Produto</title>
</head>

<body class="bg-gray-100">
    <!-- Navbar -->
    <nav class="bg-yellow-500 p-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-white text-2xl font-bold">Loja Biscuit</h1>
            <ul class="flex space-x-4 text-white">
                <li><a href="index.php" class="hover:text-gray-300">Produtos</a></li>
                <li><a href="#about" class="hover:text-gray-300">Sobre Nós</a></li>
                <li><a href="#contact" class="hover:text-gray-300">Contato</a></li>
                <?php if (isset($_SESSION['nome'])): ?>
                    <li><a href="php/perfil.php" class="hover:text-gray-300">Meu Perfil</a></li>
                    <li><a href="php/carrinho/carrinho.php" class="hover:text-gray-300">Carrinho</a></li>
                <?php else: ?>
                    <li><a href="php/login.php" class="hover:text-gray-300">Login</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <!-- Detalhes do Produto -->
    <section class="container mx-auto p-6">
        <?php if ($produto): ?>
            <div class="bg-white p-6 rounded-lg shadow-md flex flex-col lg:flex-row">
                <div class="lg:w-1/2 mb-6 lg:mb-0">
                    <img src="php/exibir_imagem.php?id=<?php echo htmlspecialchars($produto['id']); ?>" alt="<?php echo htmlspecialchars($produto['nome']); ?>" class="w-full h-auto rounded-lg shadow-lg">
                </div>
                <div class="lg:w-1/2 lg:pl-6">
                    <h2 class="text-3xl font-semibold mb-2"><?php echo htmlspecialchars($produto['nome']); ?></h2>
                    <p class="text-xl text-gray-700 mb-4"><?php echo htmlspecialchars($produto['descricao']); ?></p>
                    <p class="text-lg font-semibold text-gray-800">R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></p>

                    <form method="POST" action="php/carrinho/add_carrinho.php" class="mt-6 flex items-center">
                        <input type="hidden" name="produto_id" value="<?php echo htmlspecialchars($produto['id']); ?>">
                        <button type="submit" class="bg-yellow-500 text-white px-6 py-3 rounded-md hover:bg-yellow-600 transition duration-300">Adicionar ao Carrinho</button>
                    </form>

                    <div class="mt-4">
                        <h3 class="text-xl font-medium">Características</h3>
                        <ul class="list-disc pl-6 text-gray-600">
                            <li>Feito à mão com materiais de alta qualidade</li>
                            <li>Design único e exclusivo</li>
                            <li>Perfeito para decoração ou presente</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Exibindo Avaliações -->
            <div class="mt-6 bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-2xl font-bold mb-4">Avaliações</h3>
                <?php if ($result_avaliacoes->num_rows > 0): ?>
                    <?php while ($avaliacao = $result_avaliacoes->fetch_assoc()): ?>
                        <div class="mb-4">
                            <p class="font-semibold"><?php echo htmlspecialchars($avaliacao['usuario']); ?> - Nota: <?php echo htmlspecialchars($avaliacao['nota']); ?>/5</p>
                            <p class="text-gray-700"><?php echo nl2br(htmlspecialchars($avaliacao['comentario'])); ?></p>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="text-gray-700">Ainda não há avaliações para este produto.</p>
                <?php endif; ?>
            </div>

            <!-- Formulário de Avaliação -->
            <?php if (isset($_SESSION['nome'])): ?>
                <section class="mt-6 bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-2xl font-bold mb-4">Avaliar Produto</h3>
                    <form method="POST" action="avaliar_produto.php">
                        <div class="mb-4">
                            <label for="nota" class="block text-gray-700">Nota (1 a 5)</label>
                            <select id="nota" name="nota" class="w-full p-2 border rounded mt-2" required>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="comentario" class="block text-gray-700">Comentário (Opcional)</label>
                            <textarea id="comentario" name="comentario" rows="4" class="w-full p-2 border rounded mt-2"></textarea>
                        </div>
                        <input type="hidden" name="produto_id" value="<?php echo htmlspecialchars($produto['id']); ?>">
                        <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded">Enviar Avaliação</button>
                    </form>
                </section>
            <?php else: ?>
                <p class="text-gray-700">Você precisa estar logado para avaliar este produto.</p>
            <?php endif; ?>

        <?php else: ?>
            <p class="text-red-600">Produto não encontrado!</p>
        <?php endif; ?>
    </section>

    <!-- Footer -->
    <footer class="bg-yellow-500 text-white p-4 mt-6 text-center">
        <p>&copy; 2024 Loja do Rodrigo. Todos os direitos reservados.</p>
    </footer>
</body>
</html>
