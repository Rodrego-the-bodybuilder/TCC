<?php
session_start();
include("../conexao.php");

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: index.php");
    exit;
}

if (isset($_GET['id'])) {
    $produto_id = intval($_GET['id']);
    $resultado = $conexao->query("SELECT * FROM produtos WHERE id = $produto_id");
    $produto = $resultado->fetch_assoc();

    if (!$produto) {
        echo "<script>alert('Produto não encontrado.');</script>";
        header("Location: read.php");
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $conexao->real_escape_string(trim($_POST['nome']));
    $descricao = $conexao->real_escape_string(trim($_POST['descricao']));
    $preco = $conexao->real_escape_string(trim($_POST['preco']));

    $stmt = $conexao->prepare("UPDATE produtos SET nome = ?, descricao = ?, preco = ? WHERE id = ?");
    $stmt->bind_param("ssdi", $nome, $descricao, $preco, $produto_id);

    if ($stmt->execute()) {
        echo "<script>alert('Produto atualizado com sucesso.');</script>";
        header("Location: read.php");
    } else {
        echo "<script>alert('Erro ao atualizar o produto.');</script>";
    }

    $stmt->close();
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Atualizar Produto</title>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-6">
        <h2 class="text-4xl font-bold text-gray-700 mb-6">Atualizar Produto</h2>
        <form action="update.php?id=<?php echo $produto_id; ?>" method="POST" class="bg-white p-8 rounded-lg shadow-md max-w-lg mx-auto">
            <div class="mb-4">
                <label for="nome" class="block text-sm font-medium text-gray-700">Nome do Produto</label>
                <input type="text" name="nome" id="nome" value="<?php echo htmlspecialchars($produto['nome']); ?>" required class="mt-1 p-2 w-full border rounded focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="mb-4">
                <label for="descricao" class="block text-sm font-medium text-gray-700">Descrição</label>
                <textarea name="descricao" id="descricao" rows="4" required class="mt-1 p-2 w-full border rounded focus:ring-blue-500 focus:border-blue-500"><?php echo htmlspecialchars($produto['descricao']); ?></textarea>
            </div>
            <div class="mb-4">
                <label for="preco" class="block text-sm font-medium text-gray-700">Preço</label>
                <input type="text" name="preco" id="preco" value="<?php echo $produto['preco']; ?>" required class="mt-1 p-2 w-full border rounded focus:ring-blue-500 focus:border-blue-500">
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-500">Salvar Alterações</button>
        </form>
    </div>
</body>
</html>
