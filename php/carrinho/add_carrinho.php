<?php
session_start();
include("../../conexao.php");

// Verifique se os dados necessários estão presentes
if (!isset($_SESSION['usuario_id'])) {
    echo "Erro: Usuário não está logado.";
    exit;
}
if (!isset($_POST['produto_id'])) {
    echo "Erro: Produto não foi especificado.";
    exit;
}
if (!isset($_POST['quantidade'])) {
    echo "Erro: Quantidade não foi especificada.";
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$produto_id = $_POST['produto_id'];
$quantidade = $_POST['quantidade'];

// Validação de quantidade
if (!is_numeric($quantidade) || $quantidade <= 0) {
    $quantidade = 1;
}

// Verifica se o produto já está no carrinho
$check = $conexao->prepare("SELECT quantidade FROM carrinho WHERE usuario_id = ? AND produto_id = ?");
$check->bind_param("ii", $usuario_id, $produto_id);
$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {
    // Atualiza a quantidade se o produto já estiver no carrinho
    $row = $result->fetch_assoc();
    $nova_quantidade = $row['quantidade'] + $quantidade;

    $update = $conexao->prepare("UPDATE carrinho SET quantidade = ? WHERE usuario_id = ? AND produto_id = ?");
    $update->bind_param("iii", $nova_quantidade, $usuario_id, $produto_id);
    $update->execute();
} else {
    // Adiciona novo produto ao carrinho
    $insert = $conexao->prepare("INSERT INTO carrinho (usuario_id, produto_id, quantidade) VALUES (?, ?, ?)");
    $insert->bind_param("iii", $usuario_id, $produto_id, $quantidade);
    $insert->execute();
}

// Redireciona para o carrinho após a operação
header("Location: carrinho.php");
exit;
?>
