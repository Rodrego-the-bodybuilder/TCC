<?php
include("../conexao.php"); // Conexão com o banco de dados

if (isset($_GET['id'])) {
    $id = (int)$_GET['id']; // Obtém o ID do produto

    // Prepara a consulta para evitar injeções SQL
    $stmt = $conexao->prepare("SELECT imagem FROM produtos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($imagem);
        $stmt->fetch();

        // Define os cabeçalhos para a imagem
        header("Content-Type: image/jpeg"); // ou image/png dependendo do tipo da imagem
        echo $imagem; // Mostra a imagem
    } else {
        echo "Imagem não encontrada.";
    }

    $stmt->close();
} else {
    echo "ID inválido.";
}
?>
