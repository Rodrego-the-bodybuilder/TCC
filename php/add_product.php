<?php
include("./conexao.php");
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];

    $sql = "INSERT INTO produtos (nome, descricao, preco) VALUES ('$nome', '$descricao', '$preco')";
    
    if ($conn->query($sql) === TRUE) {
        echo "<div class='text-green-500'>Produto adicionado com sucesso!</div>";
    } else {
        echo "<div class='text-red-500'>Erro ao adicionar o produto: " . $conn->error . "</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Produto</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.1.2/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-md w-96">
        <h2 class="text-2xl font-bold mb-6 text-center">Adicionar Produto</h2>
        <form action="" method="POST" class="space-y-4">
            <div>
                <label for="nome" class="block text-sm font-medium text-gray-700">Nome do Produto</label>
                <input type="text" name="nome" class="w-full p-2 border border-gray-300 rounded-md" placeholder="Nome do Produto" required>
            </div>
            <div>
                <label for="descricao" class="block text-sm font-medium text-gray-700">Descrição</label>
                <textarea name="descricao" class="w-full p-2 border border-gray-300 rounded-md" placeholder="Descrição do Produto" required></textarea>
            </div>
            <div>
                <label for="preco" class="block text-sm font-medium text-gray-700">Preço (R$)</label>
                <input type="number" step="0.01" name="preco" class="w-full p-2 border border-gray-300 rounded-md" placeholder="Preço" required>
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded-md">Adicionar Produto</button>
        </form>
        <div class="text-center mt-4">
            <a href="../admin/dashboard.php" class="text-blue-500 hover:underline">Voltar ao Dashboard</a>
        </div>
    </div>
</body>
</html>