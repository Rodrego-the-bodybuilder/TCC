<?php
session_start();
include("../../conexao.php");

// Verifique se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    die("Erro: Usuário não está logado.");
}

// Verifique se o ID do produto foi especificado
if (!isset($_POST['produto_id'])) {
    die("Erro: Produto não foi especificado.");
}

// Verifique se a quantidade foi especificada e é válida
if (!isset($_POST['quantidade']) || !is_numeric($_POST['quantidade']) || $_POST['quantidade'] <= 0) {
    die("Erro: Quantidade inválida.");
}

$usuario_id = $_SESSION['usuario_id'];
$produto_id = (int)$_POST['produto_id'];
$quantidade = (int)$_POST['quantidade'];  // Garantir que a quantidade seja convertida para inteiro

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

// Libere recursos
$check->close();
if (isset($update)) $update->close();
if (isset($insert)) $insert->close();
$conexao->close();

// Redireciona para o carrinho após a operação
header("Location: carrinho.php");
exit;
?>
