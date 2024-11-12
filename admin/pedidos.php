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

// Consulta para obter os pedidos, incluindo o nome e o e-mail do usuário
$resultado = $conexao->query("
    SELECT p.id, p.usuario_id, p.total, e.rua, e.numero, e.complemento, e.cidade, e.estado, e.cep, p.status_pagamento, u.nome, u.email
    FROM pedidos p
    JOIN enderecos_entrega e ON p.usuario_id = e.usuario_id
    JOIN usuarios u ON p.usuario_id = u.id
    ORDER BY p.id DESC
");
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Pedidos</title>
</head>

<body class="bg-gray-100">
    
    <nav class="bg-yellow-500 p-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-white text-2xl font-bold">Loja Biscuit</h1>
            <ul class="hidden md:flex space-x-4 text-white">
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
        <h2 class="text-4xl font-bold text-gray-700 mb-6">Pedidos</h2>

        <div class="overflow-x-auto bg-white shadow-md rounded-lg">
            <table class="min-w-full table-auto">
                <thead>
                    <tr class="bg-yellow-500 text-white">
                        <th class="py-2 px-4">ID Pedido</th>
                        <th class="py-2 px-4">Nome do Usuário</th>
                        <th class="py-2 px-4">E-mail</th>
                        <th class="py-2 px-4">Total</th>
                        <th class="py-2 px-4">Endereço</th>
                        <th class="py-2 px-4">Status Pagamento</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($pedido = $resultado->fetch_assoc()): ?>
                        <tr>
                            <td class="py-2 px-4"><?php echo htmlspecialchars($pedido['id']); ?></td>
                            <td class="py-2 px-4"><?php echo htmlspecialchars($pedido['nome']); ?></td>
                            <td class="py-2 px-4"><?php echo htmlspecialchars($pedido['email']); ?></td>
                            <td class="py-2 px-4">R$ <?php echo number_format($pedido['total'], 2, ',', '.'); ?></td>
                            <td class="py-2 px-4">
                                <?php echo htmlspecialchars($pedido['rua']) . ", " . htmlspecialchars($pedido['numero']) . " - " . 
                                     htmlspecialchars($pedido['complemento']) . ", " . htmlspecialchars($pedido['cidade']) . " - " . 
                                     htmlspecialchars($pedido['estado']) . ", CEP: " . htmlspecialchars($pedido['cep']); ?>
                            </td>
                            <td class="py-2 px-4"><?php echo htmlspecialchars($pedido['status_pagamento']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
