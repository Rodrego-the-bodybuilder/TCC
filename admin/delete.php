<?php
session_start();
include("../conexao.php");

// Verifica se o usuário é admin
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: index.php");
    exit;
}

// Verifica se um ID foi passado para exclusão
if (isset($_GET['id'])) {
    $delete_id = intval($_GET['id']);
    $stmt = $conexao->prepare("DELETE FROM produtos WHERE id = ?");
    $stmt->bind_param("i", $delete_id);

    if ($stmt->execute()) {
        echo "<script>alert('Produto excluído com sucesso.');</script>";
    } else {
        echo "<script>alert('Erro ao excluir o produto.');</script>";
    }

    $stmt->close();
    header("Location: read.php");
    exit;
} else {
    header("Location: read.php");
    exit;
}
?>
