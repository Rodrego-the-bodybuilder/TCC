<?php 
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../index.php");
    exit();
}
include '../php/db.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.1.2/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <h1 class="text-3xl">Admin Dashboard</h1>

    <!-- Interface para adicionar produto -->
    <form action="../php/add_product.php" method="POST">
        <input type="text" name="nome" placeholder="Nome do Produto">
        <textarea name="descricao" placeholder="Descrição"></textarea>
        <input type="number" name="preco" placeholder="Preço">
        <button type="submit">Adicionar Produto</button>
    </form>
</body>
</html>
