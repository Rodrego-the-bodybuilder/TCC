<?php
session_start();
include("../../conexao.php");

$usuario_id = $_SESSION['usuario_id'];

// Cria o pedido com status 'Pendente'
$total = $conexao->query("
    SELECT SUM(p.preco * c.quantidade) AS total 
    FROM carrinho AS c 
    JOIN produtos AS p ON c.produto_id = p.id 
    WHERE c.usuario_id = $usuario_id
")->fetch_assoc()['total'];

$stmt = $conexao->prepare("INSERT INTO pedidos (usuario_id, total) VALUES (?, ?)");
$stmt->bind_param("id", $usuario_id, $total);
$stmt->execute();
$pedido_id = $stmt->insert_id;

// Transfere itens do carrinho para itens_pedido
$itens = $conexao->query("
    SELECT produto_id, quantidade, preco FROM carrinho 
    JOIN produtos ON carrinho.produto_id = produtos.id 
    WHERE usuario_id = $usuario_id
");

while ($item = $itens->fetch_assoc()) {
    $insert = $conexao->prepare("
        INSERT INTO itens_pedido (pedido_id, produto_id, quantidade, preco_unitario) 
        VALUES (?, ?, ?, ?)
    ");
    $insert->bind_param("iiid", $pedido_id, $item['produto_id'], $item['quantidade'], $item['preco']);
    $insert->execute();
}

// Limpa o carrinho
$conexao->query("DELETE FROM carrinho WHERE usuario_id = $usuario_id");

echo "<script>alert('Pedido finalizado com sucesso!'); window.location.href = 'index.php';</script>";
?>
