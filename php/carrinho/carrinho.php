<?php
session_start();
include("../../conexao.php");

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

// Query para exibir o carrinho
$query = "
    SELECT c.id, p.nome, p.preco, c.quantidade, (p.preco * c.quantidade) AS subtotal 
    FROM carrinho AS c 
    JOIN produtos AS p ON c.produto_id = p.id 
    WHERE c.usuario_id = ?
";
$stmt = $conexao->prepare($query);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Carrinho</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
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
    <div class="container mx-auto p-6">
        <h2 class="text-3xl font-bold mb-4">Meu Carrinho</h2>

        <?php if ($result->num_rows > 0): ?>
            <div class="grid gap-6 lg:grid-cols-1">
                <?php $total = 0; ?>
                <?php while ($item = $result->fetch_assoc()): ?>
                    <div class="bg-white rounded-lg shadow-md p-6 flex justify-between items-center">
                        <div>
                            <h3 class="text-xl font-semibold"><?php echo htmlspecialchars($item['nome']); ?></h3>
                            <p class="text-gray-700">Quantidade: <?php echo (int) $item['quantidade']; ?></p>
                            <p class="text-gray-700">Subtotal: R$ <?php echo number_format($item['subtotal'], 2, ',', '.'); ?>
                            </p>
                        </div>
                        <form action="remover_carrinho.php" method="POST">
                            <input type="hidden" name="id" value="<?php echo (int) $item['id']; ?>">
                            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">Remover</button>
                        </form>
                    </div>
                    <?php $total += $item['subtotal']; ?>
                <?php endwhile; ?>
            </div>

            <div class="mt-6 flex justify-between items-center">
                <a href="finalizar_pedido.php"
                    class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-500">Finalizar Pedido</a>
                <a href="carrinho.php" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-500">Continuar
                    Comprando</a>
            </div>

            <div class="mt-6 text-right">
                <p class="text-xl font-bold">Total: R$ <?php echo number_format($total, 2, ',', '.'); ?></p>
            </div>
        <?php else: ?>
            <p class="text-lg font-semibold text-gray-700">Seu carrinho está vazio.</p>
        <?php endif; ?>
    </div>
</body>

</html>

<?php
$stmt->close();
$conexao->close();
?>