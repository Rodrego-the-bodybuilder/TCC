<?php
session_start();
include("../../conexao.php"); // Conexão com o banco de dados

// Verifica se o usuário está logado e se o produto e a avaliação foram passados
if (isset($_SESSION['usuario_id'], $_POST['produto_id'], $_POST['nota'])) {
    $usuario_id = $_SESSION['usuario_id']; // ID do usuário logado
    $produto_id = $_POST['produto_id']; // ID do produto
    $nota = $_POST['nota']; // Nota do produto
    $comentario = isset($_POST['comentario']) ? $_POST['comentario'] : ''; // Comentário opcional

    // Verifica se a nota está entre 1 e 5
    if ($nota >= 1 && $nota <= 5) {
        // Query para inserir a avaliação no banco
        $query = "INSERT INTO avaliacoes (usuario_id, produto_id, nota, comentario) VALUES (?, ?, ?, ?)";
        $stmt = $conexao->prepare($query);
        $stmt->bind_param("iiis", $usuario_id, $produto_id, $nota, $comentario);

        if ($stmt->execute()) {
            // Redireciona de volta para a página do produto após a avaliação
            header("Location: produto.php?id=" . $produto_id);
            exit();
        } else {
            echo "Erro ao salvar avaliação.";
        }
    } else {
        echo "Nota inválida. Deve ser entre 1 e 5.";
    }
} else {
    echo "Erro: Dados insuficientes.";
}
?>
