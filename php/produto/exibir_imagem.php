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

    // Debugging: Verificar o caminho da imagem e se o arquivo existe
    var_dump($imagem);
    var_dump(file_exists("../../admin/uploads/" . $imagem));

    // Verifica se a imagem existe no caminho especificado
    if (!empty($imagem) && file_exists("../../admin/uploads/" . $imagem)) {
        $mime = mime_content_type("../../admin/uploads/" . $imagem);
        header("Content-Type: $mime");
        readfile("../" . $imagem);
    } else {
        // Exibe a imagem padrão em caso de erro
        header("Content-Type: image/png");
        echo file_get_contents("../assets/imagem_nao_encontrada.png");
    }
} else {
    echo "ID inválido.";
}
?>
