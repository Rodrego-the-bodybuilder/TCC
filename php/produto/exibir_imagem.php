<?php
include("../conexao.php"); // Conexão com o banco de dados

if (isset($_GET['id'])) {
    $id = (int)$_GET['id']; // Obtém o ID do produto

    // Prepara a consulta para buscar o caminho da imagem
    $stmt = $conexao->prepare("SELECT imagem FROM produtos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($imagem);
    $stmt->fetch();
    $stmt->close();

    if (!empty($imagem) && file_exists("../../" . $imagem)) {
        // Define os cabeçalhos para exibir a imagem
        $extensao = pathinfo($imagem, PATHINFO_EXTENSION);
        $mime = mime_content_type("../../" . $imagem);
        header("Content-Type: $mime");
        readfile("../../" . $imagem);
    } else {
        // Retorna uma mensagem ou imagem padrão em caso de erro
        header("Content-Type: image/png");
        echo file_get_contents("../assets/imagem_nao_encontrada.png");
    }
} else {
    echo "ID inválido.";
}
?>
