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

    // Inicia a transação para garantir que as operações sejam feitas de forma atômica
    $conexao->begin_transaction();

    try {
        // Primeiro, excluir as avaliações relacionadas ao produto
        $stmt_avaliacoes = $conexao->prepare("DELETE FROM avaliacoes WHERE produto_id = ?");
        $stmt_avaliacoes->bind_param("i", $delete_id);
        if (!$stmt_avaliacoes->execute()) {
            throw new Exception("Erro ao excluir as avaliações do produto.");
        }

        // Excluir os itens de pedido relacionados ao produto
        $stmt_itens_pedido = $conexao->prepare("DELETE FROM itens_pedido WHERE produto_id = ?");
        $stmt_itens_pedido->bind_param("i", $delete_id);
        if (!$stmt_itens_pedido->execute()) {
            throw new Exception("Erro ao excluir os itens de pedido do produto.");
        }

        // Agora, excluir o produto
        $stmt_produto = $conexao->prepare("DELETE FROM produtos WHERE id = ?");
        $stmt_produto->bind_param("i", $delete_id);
        if (!$stmt_produto->execute()) {
            throw new Exception("Erro ao excluir o produto.");
        }

        // Se tudo deu certo, confirma a transação
        $conexao->commit();

        echo "<script>alert('Produto, suas avaliações e itens de pedido excluídos com sucesso.');</script>";
    } catch (Exception $e) {
        // Se houve algum erro, desfaz a transação
        $conexao->rollback();
        echo "<script>alert('Erro: " . $e->getMessage() . "');</script>";
    }

    $stmt_avaliacoes->close();
    $stmt_itens_pedido->close();
    $stmt_produto->close();
    
    // Redireciona para a página de leitura de produtos
    header("Location: read.php");
    exit;
} else {
    header("Location: read.php");
    exit;
}
?>
