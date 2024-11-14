<?php
include("../../conexao.php"); // Conexão com o banco de dados

if (isset($_GET['id'])) {
    $id = (int)$_GET['id']; // Obtém o ID do produto

    // Prepara a consulta para buscar a imagem em Base64
    $stmt = $conexao->prepare("SELECT imagem FROM produtos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($imagemBase64);
    $stmt->fetch();
    $stmt->close();

    if (!empty($imagemBase64)) {
        // Identifica o tipo de conteúdo da imagem (ajustar caso precise forçar um MIME)
        $finfo = finfo_open();
        $mimeType = finfo_buffer($finfo, base64_decode($imagemBase64), FILEINFO_MIME_TYPE);
        finfo_close($finfo);

        // Define o cabeçalho e exibe a imagem
        header("Content-Type: $mimeType");
        echo base64_decode($imagemBase64);
    } else {
        // Exibe a imagem padrão em caso de erro
        header("Content-Type: image/png");
        echo file_get_contents("../assets/imagem_nao_encontrada.png");
    }
} else {
    echo "ID inválido.";
}
?>
