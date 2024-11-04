<?php
session_start();
include("../../conexao.php");

$id = $_POST['id'];

// Remover item do carrinho
$delete = $conexao->prepare("DELETE FROM carrinho WHERE id = ?");
$delete->bind_param("i", $id);
$delete->execute();

header("Location: carrinho.php");
exit;
?>
