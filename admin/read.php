<?php
session_start();
include("../conexao.php");

// Verifica se o usuário é admin
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: index.php");
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
            -webkit-line-clamp: 4; /* Limita a 4 linhas */
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: normal;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-4xl font-bold text-gray-700">Lista de Produtos</h2>
            <a href="create.php" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-500">Adicionar Produto</a>
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
                        <a href="update.php?id=<?php echo $produto['id']; ?>" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Atualizar</a>
                        <a href="delete.php?id=<?php echo $produto['id']; ?>" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600" onclick="return confirm('Tem certeza que deseja deletar?');">Deletar</a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>
