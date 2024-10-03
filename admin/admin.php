<?php
session_start();
include("conexao.php"); // Conexão com o banco de dados

// Verifica se o usuário é admin
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: index.php");
    exit;
}

// Adiciona o produto ao banco de dados
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];

    // Lida com o upload da imagem
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0) {
        $imagemNome = $_FILES['imagem']['name'];
        $imagemTmp = $_FILES['imagem']['tmp_name'];
        $imagemDestino = 'uploads/' . $imagemNome;

        // Move o arquivo enviado para a pasta de uploads
        if (move_uploaded_file($imagemTmp, $imagemDestino)) {
            // Inserir o produto com imagem no banco de dados
            $query = "INSERT INTO produtos (nome, descricao, preco, imagem) VALUES ('$nome', '$descricao', '$preco', '$imagemDestino')";
            $conexao->query($query);
            echo "Produto adicionado com sucesso!";
        } else {
            echo "Falha ao fazer upload da imagem.";
        }
    } else {
        echo "Nenhuma imagem foi enviada.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Adicionar Produto</title>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-6">
        <h2 class="text-3xl font-bold mb-4">Adicionar Novo Produto</h2>
        <form action="admin.php" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow">
            <div class="mb-4">
                <label for="nome" class="block text-sm font-medium text-gray-700">Nome do Produto</label>
                <input type="text" name="nome" id="nome" required class="mt-1 p-2 w-full border rounded">
            </div>
            <div class="mb-4">
                <label for="descricao" class="block text-sm font-medium text-gray-700">Descrição</label>
                <textarea name="descricao" id="descricao" rows="4" required class="mt-1 p-2 w-full border rounded"></textarea>
            </div>
            <div class="mb-4">
                <label for="preco" class="block text-sm font-medium text-gray-700">Preço</label>
                <input type="text" name="preco" id="preco" required class="mt-1 p-2 w-full border rounded">
            </div>
            <div class="mb-4">
                <label for="imagem" class="block text-sm font-medium text-gray-700">Imagem do Produto</label>
                <input type="file" name="imagem" id="imagem" class="mt-1">
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Adicionar Produto</button>
        </form>
    </div>
</body>
</html>
