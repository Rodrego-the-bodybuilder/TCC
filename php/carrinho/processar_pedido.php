<?php
session_start();
include("../../conexao.php");

if (!isset($_POST['usuario_id'])) {
    echo "Erro: Usuário não autenticado.";
    exit;
}

$usuario_id = $_POST['usuario_id'];
$total = $_POST['total'];
$rua = $_POST['rua'];
$numero = $_POST['numero'];
$complemento = $_POST['complemento'];
$cidade = $_POST['cidade'];
$estado = $_POST['estado'];
$cep = $_POST['cep'];
$metodo_pagamento = $_POST['metodo_pagamento'];

// Inserir pedido
$stmt_pedido = $conexao->prepare("INSERT INTO pedidos (usuario_id, total) VALUES (?, ?)");
$stmt_pedido->bind_param("id", $usuario_id, $total);
$stmt_pedido->execute();
$pedido_id = $stmt_pedido->insert_id; // Obtém o ID do pedido inserido

// Inserir endereço de entrega
$stmt_endereco = $conexao->prepare("INSERT INTO enderecos_entrega (usuario_id, rua, numero, complemento, cidade, estado, cep) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt_endereco->bind_param("issssss", $usuario_id, $rua, $numero, $complemento, $cidade, $estado, $cep);
$stmt_endereco->execute();

// Inserir pagamento
$stmt_pagamento = $conexao->prepare("INSERT INTO pagamentos (pedido_id, valor, metodo_pagamento, status_pagamento) VALUES (?, ?, ?, 'Pendente')");
$stmt_pagamento->bind_param("ids", $pedido_id, $total, $metodo_pagamento);
$stmt_pagamento->execute();

// Inserir itens do pedido
$stmt_itens = $conexao->prepare("INSERT INTO itens_pedido (pedido_id, produto_id, quantidade, preco_unitario) SELECT ?, produto_id, quantidade, preco FROM carrinho WHERE usuario_id = ?");
$stmt_itens->bind_param("ii", $pedido_id, $usuario_id);
$stmt_itens->execute();


// Limpar o carrinho
$stmt_limpar_carrinho = $conexao->prepare("DELETE FROM carrinho WHERE usuario_id = ?");
$stmt_limpar_carrinho->bind_param("i", $usuario_id);
$stmt_limpar_carrinho->execute();

// Redirecionar para a página de sucesso
header("Location: sucesso.php");
exit;
?>
