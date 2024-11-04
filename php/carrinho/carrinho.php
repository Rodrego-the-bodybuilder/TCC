<?php
session_start();
include("../../conexao.php");

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php"); // Caminho absoluto para o login
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

$query = "
    SELECT c.id, p.nome, p.preco, c.quantidade, (p.preco * c.quantidade) AS subtotal 
    FROM carrinho AS c 
    JOIN produtos AS p ON c.produto_id = p.id 
    WHERE c.usuario_id = $usuario_id
";
$result = $conexao->query($query);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Carrinho</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-6">
        <h2 class="text-3xl font-bold mb-4">Meu Carrinho</h2>
        
        <?php if ($result->num_rows > 0): ?>
            <div class="grid gap-6 lg:grid-cols-2">
                <?php while ($item = $result->fetch_assoc()): ?>
                    <div class="bg-white rounded-lg shadow-md p-6 flex justify-between items-center">
                        <div>
                            <h3 class="text-xl font-semibold"><?php echo htmlspecialchars($item['nome']); ?></h3>
                            <p class="text-gray-700">Quantidade: <?php echo $item['quantidade']; ?></p>
                            <p class="text-gray-700">Subtotal: R$ <?php echo number_format($item['subtotal'], 2, ',', '.'); ?></p>
                        </div>
                        <form action="remover_carrinho.php" method="POST">
                            <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">Remover</button>
                        </form>
                    </div>
                <?php endwhile; ?>
            </div>
            <div class="mt-6">
                <a href="finalizar_pedido.php" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-500">Finalizar Pedido</a>
            </div>
        <?php else: ?>
            <p>Seu carrinho está vazio.</p>
        <?php endif; ?>
    </div>
</body>
</html>