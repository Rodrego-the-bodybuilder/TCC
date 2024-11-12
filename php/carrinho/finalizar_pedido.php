<?php 
session_start();
include("../../conexao.php");

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php"); // Redireciona para a página de login se não estiver logado
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

// Obter os produtos no carrinho
$carrinho_query = $conexao->prepare("SELECT c.produto_id, p.nome, p.preco, c.quantidade
                                     FROM carrinho c
                                     JOIN produtos p ON c.produto_id = p.id
                                     WHERE c.usuario_id = ?");
$carrinho_query->bind_param("i", $usuario_id);
$carrinho_query->execute();
$carrinho_result = $carrinho_query->get_result();

// Calcular o total do pedido
$total = 0;
while ($produto = $carrinho_result->fetch_assoc()) {
    $total += $produto['preco'] * $produto['quantidade'];
}

// Obter endereço do usuário
$endereco_query = $conexao->prepare("SELECT * FROM enderecos_entrega WHERE usuario_id = ?");
$endereco_query->bind_param("i", $usuario_id);
$endereco_query->execute();
$endereco_result = $endereco_query->get_result();
$endereco = $endereco_result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finalizar Pedido</title>
    <link rel="stylesheet" href="../styles.css"> <!-- Seu arquivo CSS -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            max-width: 900px;
            margin: 20px auto;
            background-color: white;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h2, h3 {
            color: #333;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            margin: 10px 0;
        }
        input[type="text"], select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Finalizar Pedido</h2>

        <!-- Resumo do Carrinho -->
        <h3>Produtos no Carrinho</h3>
        <ul>
            <?php
            // Exibir produtos no carrinho
            $carrinho_result->data_seek(0); // Reposição o ponteiro para começar a exibição
            while ($produto = $carrinho_result->fetch_assoc()) {
                echo "<li>" . htmlspecialchars($produto['nome']) . " - Quantidade: " . $produto['quantidade'] . " - R$ " . number_format($produto['preco'], 2, ',', '.') . "</li>";
            }
            ?>
        </ul>
        <p><strong>Total: R$ <?php echo number_format($total, 2, ',', '.'); ?></strong></p>

        <!-- Formulário de Endereço e Pagamento -->
        <form action="processar_pedido.php" method="POST">
            <h3>Endereço de Entrega</h3>
            <div>
                <label for="rua">Rua:</label>
                <input type="text" id="rua" name="rua" value="<?php echo htmlspecialchars($endereco['rua'] ?? ''); ?>" required>
            </div>
            <div>
                <label for="numero">Número:</label>
                <input type="text" id="numero" name="numero" value="<?php echo htmlspecialchars($endereco['numero'] ?? ''); ?>" required>
            </div>
            <div>
                <label for="complemento">Complemento:</label>
                <input type="text" id="complemento" name="complemento" value="<?php echo htmlspecialchars($endereco['complemento'] ?? ''); ?>">
            </div>
            <div>
                <label for="cidade">Cidade:</label>
                <input type="text" id="cidade" name="cidade" value="<?php echo htmlspecialchars($endereco['cidade'] ?? ''); ?>" required>
            </div>
            <div>
                <label for="estado">Estado:</label>
                <input type="text" id="estado" name="estado" value="<?php echo htmlspecialchars($endereco['estado'] ?? ''); ?>" required>
            </div>
            <div>
                <label for="cep">CEP:</label>
                <input type="text" id="cep" name="cep" value="<?php echo htmlspecialchars($endereco['cep'] ?? ''); ?>" required>
            </div>

            <h3>Forma de Pagamento</h3>
            <div>
                <label for="metodo_pagamento">Método de Pagamento:</label>
                <select id="metodo_pagamento" name="metodo_pagamento" required>
                    <option value="Cartão de Crédito">Cartão de Crédito</option>
                    <option value="Boleto">Boleto</option>
                    <option value="Transferência">Transferência Bancária</option>
                </select>
            </div>

            <input type="hidden" name="total" value="<?php echo $total; ?>">
            <input type="hidden" name="usuario_id" value="<?php echo $usuario_id; ?>">

            <button type="submit">Confirmar Compra</button>
        </form>
    </div>
</body>
</html>
