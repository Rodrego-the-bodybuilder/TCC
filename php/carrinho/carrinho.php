<?php
session_start();
include("../../conexao.php");

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

$isAdmin = isset($_SESSION['admin']) && $_SESSION['admin'] === true;
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <nav class="bg-yellow-500 p-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-white text-2xl font-bold">Loja Biscuit</h1>
            <form action="../../index.php" method="GET" class="flex items-center space-x-2">
                <input type="text" name="search" placeholder="Buscar produtos..." class="px-4 py-2 rounded-md">
                <button type="submit" class="bg-white text-yellow-500 px-4 py-2 rounded-md">
                    <i class="fas fa-search"></i> <!-- Ícone de lupa -->
                </button>
            </form>
            <!-- Menu de navegação -->
            <ul class="hidden md:flex space-x-4 text-white" id="menu">
                <li><a href="../../index.php" class="hover:text-gray-300">Produtos</a></li>
                <li><a href="#about" class="hover:text-gray-300">Sobre Nós</a></li>
                <li><a href="#contact" class="hover:text-gray-300">Contato</a></li>
                <?php if (isset($_SESSION['nome'])): ?>
                    <li><a href="../perfil.php" class="hover:text-gray-300">Meu Perfil</a></li>
                    <li><a href="carrinho.php" class="hover:text-gray-300">Carrinho</a></li>
                    <?php if ($isAdmin): ?>
                        <li><a href="../../admin/read.php" class="hover:text-gray-300">Admin</a></li>
                    <?php endif; ?>
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
                <a href="../../index.php" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-500">Continuar
                    Comprando</a>
            </div>

            <div class="mt-6 text-right">
                <p class="text-xl font-bold">Total: R$ <?php echo number_format($total, 2, ',', '.'); ?></p>
            </div>
        <?php else: ?>
            <p class="text-lg font-semibold text-gray-700">Seu carrinho está vazio.</p>
        <?php endif; ?>
    </div>
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