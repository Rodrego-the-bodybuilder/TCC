<?php
session_start();
include("conexao.php");

// Verifica se o usuário está logado
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

// Obtém as informações do usuário logado
$user_id = $_SESSION['id'];
$sql = "SELECT * FROM usuarios WHERE id = '$user_id'";
$result = $conexao->query($sql);
$usuario = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Perfil</title>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-10 max-w-md">
        <h1 class="text-2xl font-bold text-center mb-6">Perfil do Usuário</h1>
        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-lg font-semibold">Nome: <?php echo $usuario['nome']; ?></h2>
            <p>Email: <?php echo $usuario['email']; ?></p>
            <a class="mt-4 inline-block bg-blue-600 text-white p-2 rounded" href="logout.php">Sair</a>
        </div>
    </div>
</body>
</html>
