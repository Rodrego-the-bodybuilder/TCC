<?php
session_start();
include("../../conexao.php");

// Verifica se o ID do produto foi passado
if (isset($_GET['id'])) {
    $produto_id = (int) $_GET['id'];

    // Consulta os detalhes do produto
    $query = "SELECT * FROM produtos WHERE id = ?";
    $stmt = $conexao->prepare($query);
    $stmt->bind_param("i", $produto_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $produto = $result->fetch_assoc();
    } else {
        header("Location: index.php?error=ProdutoNãoEncontrado");
        exit();
    }

    // Verifica se a variável 'pagina' foi passada, caso contrário, define a página como 1
    $pagina = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
    $limite = 4;  // Número de avaliações por página
    $offset = ($pagina - 1) * $limite;

    // Consulta as avaliações com paginação
    $query_avaliacoes = "
        SELECT avaliacoes.nota, avaliacoes.comentario, usuarios.nome AS usuario
        FROM avaliacoes
        JOIN usuarios ON avaliacoes.usuario_id = usuarios.id
        WHERE avaliacoes.produto_id = ?
        LIMIT ?, ?";
    $stmt_avaliacoes = $conexao->prepare($query_avaliacoes);
    $stmt_avaliacoes->bind_param("iii", $produto_id, $offset, $limite);
    $stmt_avaliacoes->execute();
    $result_avaliacoes = $stmt_avaliacoes->get_result();

    // Consulta o total de avaliações
    $query_total_avaliacoes = "
        SELECT COUNT(*) AS total_avaliacoes
        FROM avaliacoes
        WHERE produto_id = ?";
    $stmt_total_avaliacoes = $conexao->prepare($query_total_avaliacoes);
    $stmt_total_avaliacoes->bind_param("i", $produto_id);
    $stmt_total_avaliacoes->execute();
    $result_total_avaliacoes = $stmt_total_avaliacoes->get_result();
    $total_avaliacoes = $result_total_avaliacoes->fetch_assoc()['total_avaliacoes'];
} else {
    header("Location: index.php?error=IDProdutoNaoFornecido");
    exit();
}
// Se a requisição for via AJAX, retornamos as avaliações em JSON
if (isset($_GET['pagina'])) {
    $comentarios = [];
    while ($avaliacao = $result_avaliacoes->fetch_assoc()) {
        $comentarios[] = $avaliacao;
    }

    echo json_encode([
        'comentarios' => $comentarios,
    ]);
    exit();
}


?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Detalhes do Produto</title>
    <script>
        let paginaAtual = 1;
        const totalAvaliacoes = <?php echo $total_avaliacoes; ?>;

        function carregarMaisAvaliacoes() {
            paginaAtual++;
            fetch(`?id=<?php echo $produto_id; ?>&pagina=${paginaAtual}`)
                .then(response => response.json())
                .then(data => {
                    const comentariosContainer = document.getElementById('comentarios');
                    data.comentarios.forEach(comentario => {
                        const div = document.createElement('div');
                        div.classList.add('mb-4');
                        div.innerHTML = `<p><strong>${comentario.usuario}</strong> - Nota: ${comentario.nota}/5</p><p class="break-words">${comentario.comentario}</p>`;
                        comentariosContainer.appendChild(div);
                    });

                    // Verifica se todas as avaliações foram carregadas
                    if (paginaAtual * 4 >= totalAvaliacoes) {
                        document.getElementById('btnCarregarMais').style.display = 'none';
                    }
                });
        }

    </script>
</head>

<body class="bg-gray-100">
    <!-- Navbar -->
    <nav class="bg-yellow-500 p-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-white text-2xl font-bold">Loja Biscuit</h1>
            <ul class="flex space-x-4 text-white">
                <li><a href="../../index.php" class="hover:text-gray-300">Produtos</a></li>
                <li><a href="#about" class="hover:text-gray-300">Sobre Nós</a></li>
                <li><a href="#contact" class="hover:text-gray-300">Contato</a></li>
                <?php if (isset($_SESSION['nome'])): ?>
                    <li><a href="../perfil.php" class="hover:text-gray-300">Meu Perfil</a></li>
                    <li><a href="../carrinho/carrinho.php" class="hover:text-gray-300">Carrinho</a></li>
                <?php else: ?>
                    <li><a href="../login.php" class="hover:text-gray-300">Login</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <!-- Detalhes do Produto -->
    <section class="container mx-auto p-6">
        <?php if ($produto): ?>
            <div class="bg-white p-6 rounded-lg shadow-md flex flex-col lg:flex-row">
                <!-- Imagem -->
                <div class="lg:w-1/2 mb-6 lg:mb-0">
                    <img src="exibir_imagem.php?id=<?php echo htmlspecialchars($produto['id']); ?>"
                        alt="<?php echo htmlspecialchars($produto['nome']); ?>" class="w-full h-auto rounded-lg shadow-lg">
                </div>
                <!-- Informações do Produto -->
                <div class="lg:w-1/2 lg:pl-6">
                    <h2 class="text-3xl font-semibold mb-2 break-words"><?php echo htmlspecialchars($produto['nome']); ?>
                    </h2>
                    <p class="text-xl text-gray-700 mb-4 break-words"><?php echo htmlspecialchars($produto['descricao']); ?>
                    </p>
                    <p class="text-lg font-semibold text-gray-800">R$
                        <?php echo number_format($produto['preco'], 2, ',', '.'); ?>
                    </p>

                    <!-- Adicionar ao Carrinho -->
                    <form method="POST" action="php/carrinho/add_carrinho.php" class="mt-6">
                        <input type="hidden" name="produto_id" value="<?php echo htmlspecialchars($produto['id']); ?>">
                        <button type="submit"
                            class="bg-yellow-500 text-white px-6 py-3 rounded-md hover:bg-yellow-600 transition duration-300">
                            Adicionar ao Carrinho
                        </button>
                    </form>
                </div>
            </div>

            <!-- Avaliações -->
            <div class="mt-6 bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-2xl font-bold mb-4">Avaliações</h3>
                <div id="comentarios">
                    <?php while ($avaliacao = $result_avaliacoes->fetch_assoc()): ?>
                        <div class="mb-4">
                            <p><strong><?php echo htmlspecialchars($avaliacao['usuario']); ?></strong> - Nota:
                                <?php echo htmlspecialchars($avaliacao['nota']); ?>/5
                            </p>
                            <p class="break-words"><?php echo nl2br(htmlspecialchars($avaliacao['comentario'])); ?></p>
                        </div>
                    <?php endwhile; ?>
                </div>
                <?php if ($total_avaliacoes > 4): ?>
                    <button id="btnCarregarMais" onclick="carregarMaisAvaliacoes()"
                        class="bg-yellow-500 text-white px-6 py-2 rounded-md hover:bg-yellow-600 mt-4">
                        Carregar Mais Avaliações
                    </button>
                <?php endif; ?>
            </div>


            <!-- Formulário de Avaliação -->
            <?php if (isset($_SESSION['usuario_id'])): ?>
                <div class="mt-6 bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-2xl font-bold mb-4">Deixe sua Avaliação</h3>
                    <form action="avaliar_produto.php" method="POST" class="space-y-4">
                        <input type="hidden" name="produto_id" value="<?php echo $produto_id; ?>">
                        <div>
                            <label for="nota" class="block text-lg font-medium text-gray-700">Nota (1-5)</label>
                            <select name="nota" id="nota" required
                                class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:ring-yellow-500">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                        </div>
                        <div>
                            <label for="comentario" class="block text-lg font-medium text-gray-700">Comentário</label>
                            <textarea name="comentario" id="comentario" rows="4" required
                                class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:ring-yellow-500"></textarea>
                        </div>
                        <button type="submit" class="bg-yellow-500 text-white px-6 py-2 rounded-md hover:bg-yellow-600">
                            Enviar Avaliação
                        </button>
                    </form>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </section>

</body>

</html>