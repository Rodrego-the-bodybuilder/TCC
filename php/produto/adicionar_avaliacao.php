<?php
session_start();
include("../../conexao.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['usuario_id'])) {
    $produto_id = (int) $_POST['produto_id'];
    $usuario_id = (int) $_SESSION['usuario_id'];
    $nota = (int) $_POST['nota'];
    $comentario = trim($_POST['comentario']);

    if ($nota < 1 || $nota > 5 || empty($comentario)) {
        header("Location: detalhes_produto.php?id=$produto_id&error=DadosInvalidos");
        exit();
    }

    $query = "INSERT INTO avaliacoes (produto_id, usuario_id, nota, comentario) VALUES (?, ?, ?, ?)";
    $stmt = $conexao->prepare($query);
    $stmt->bind_param("iiis", $produto_id, $usuario_id, $nota, $comentario);

    if ($stmt->execute()) {
        header("Location: detalhes_produto.php?id=$produto_id&success=AvaliacaoAdicionada");
    } else {
        header("Location: detalhes_produto.php?id=$produto_id&error=ErroAoAdicionar");
    }
} else {
    header("Location: ../../login.php");
}
?>
