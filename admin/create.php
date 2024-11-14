<?php
session_start();
include("../conexao.php"); // Conexão com o banco de dados

// Verifica se o usuário é admin
$isAdmin = isset($_SESSION['admin']) && $_SESSION['admin'] === true;

if (!$isAdmin) {
    header("Location: index.php");
    exit;
}

// Adiciona o produto ao banco de dados
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $conexao->real_escape_string(trim($_POST['nome']));
    $descricao = $conexao->real_escape_string(trim($_POST['descricao']));
    $preco = $conexao->real_escape_string(trim($_POST['preco']));

    // Validação básica
    if (empty($nome) || empty($descricao) || empty($preco)) {
        echo "<script>alert('Por favor, preencha todos os campos.');</script>";
    } else {
        // Lida com o upload da imagem
        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0) {
            // Lê o conteúdo da imagem e converte para Base64
            $imagem = base64_encode(file_get_contents($_FILES['imagem']['tmp_name']));

            // Inserir o produto com imagem no banco de dados usando prepared statements
            $stmt = $conexao->prepare("INSERT INTO produtos (nome, descricao, preco, imagem) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssds", $nome, $descricao, $preco, $imagem);

            if ($stmt->execute()) {
                echo "<script>alert('Produto adicionado com sucesso!');</script>";
            } else {
                echo "<script>alert('Erro ao adicionar produto: " . $stmt->error . "');</script>";
            }

            $stmt->close();
        } else {
            echo "<script>alert('Nenhuma imagem foi enviada ou houve um erro no upload.');</script>";
        }
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
    <!-- Navbar -->
    <nav class="bg-yellow-500 p-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-white text-2xl font-bold">Loja Biscuit</h1>
            <button class="md:hidden text-white" id="menu-toggle">
                <!-- Ícone do menu hamburguer -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                </svg>
            </button>
            <ul class="hidden md:flex space-x-4 text-white" id="menu">
                <li><a href="../index.php" class="hover:text-gray-300">Produtos</a></li>
                <li><a href="#about" class="hover:text-gray-300">Sobre Nós</a></li>
                <li><a href="#contact" class="hover:text-gray-300">Contato</a></li>
                <?php if (isset($_SESSION['nome'])): ?>
                    <li><a href="../php/perfil.php" class="hover:text-gray-300">Meu Perfil</a></li>
                    <li><a href="../php/carrinho/carrinho.php" class="hover:text-gray-300">Carrinho</a></li>
                    <?php if ($isAdmin): ?>
                        <li><a href="read.php" class="hover:text-gray-300">Admin</a></li>
                    <?php endif; ?>
                <?php else: ?>
                    <li><a href="../php/login.php" class="hover:text-gray-300">Login</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
    <div class="container mx-auto p-6">
        <h2 class="text-4xl font-bold text-gray-700 mb-6">Adicionar Novo Produto</h2>
        <form action="create.php" method="POST" enctype="multipart/form-data"
            class="bg-white p-8 rounded-lg shadow-md max-w-lg mx-auto">
            <div class="mb-4">
                <label for="nome" class="block text-sm font-medium text-gray-700">Nome do Produto</label>
                <input type="text" name="nome" id="nome" required
                    class="mt-1 p-2 w-full border rounded focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="mb-4">
                <label for="descricao" class="block text-sm font-medium text-gray-700">Descrição</label>
                <textarea name="descricao" id="descricao" rows="4" required
                    class="mt-1 p-2 w-full border rounded focus:ring-blue-500 focus:border-blue-500"></textarea>
            </div>
            <div class="mb-4">
                <label for="preco" class="block text-sm font-medium text-gray-700">Preço</label>
                <input type="text" name="preco" id="preco" required
                    class="mt-1 p-2 w-full border rounded focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="mb-4">
                <label for="imagem" class="block text-sm font-medium text-gray-700">Imagem do Produto</label>
                <input type="file" name="imagem" id="imagem"
                    class="mt-1 p-2 w-full border rounded focus:ring-blue-500 focus:border-blue-500">
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-500">Adicionar
                Produto</button>
        </form>
    </div>
</body>

</html>