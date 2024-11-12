<?php
// Conexão com o banco de dados
include("../conexao.php");

// Verificando se o formulário de pesquisa foi enviado
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';

// Filtrando os produtos com base na pesquisa e faixa de preço
$minPrice = isset($_GET['min_price']) ? $_GET['min_price'] : 0;
$maxPrice = isset($_GET['max_price']) ? $_GET['max_price'] : 10000; // Preço máximo por padrão

$sql = "SELECT * FROM produtos WHERE nome LIKE '%$searchQuery%' AND preco BETWEEN $minPrice AND $maxPrice";
$result = mysqli_query($conn, $sql);

// Definindo o número total de produtos
$totalProducts = mysqli_num_rows($result);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados da Pesquisa</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.0/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>

    <!-- HEADER -->
    <nav class="bg-yellow-500 p-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-white text-2xl font-bold">Loja Biscuit</h1>
            <form action="index.php" method="GET" class="flex items-center space-x-2">
                <input type="text" name="search" placeholder="Buscar produtos..." class="px-4 py-2 rounded-md" value="<?php echo $searchQuery; ?>">
                <button type="submit" class="bg-white text-yellow-500 px-4 py-2 rounded-md">Buscar</button>
            </form>
        </div>
    </nav>

    <!-- CONTAINER -->
    <div class="container mx-auto my-8 flex">
        
        <!-- Barra Lateral -->
        <div class="w-1/4 p-4 border-r">
            <h3 class="text-xl font-semibold mb-4">Filtrar por preço</h3>
            <form action="index.php" method="GET">
                <input type="hidden" name="search" value="<?php echo $searchQuery; ?>">
                <label for="min_price" class="block">Preço mínimo:</label>
                <input type="number" id="min_price" name="min_price" class="border px-2 py-1 mb-4" value="<?php echo $minPrice; ?>">
                
                <label for="max_price" class="block">Preço máximo:</label>
                <input type="number" id="max_price" name="max_price" class="border px-2 py-1 mb-4" value="<?php echo $maxPrice; ?>">
                
                <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded-md w-full">Filtrar</button>
            </form>
        </div>

        <!-- Resultados -->
        <div class="w-3/4 p-4">
            <h2 class="text-2xl font-semibold mb-4">Resultados da Pesquisa</h2>
            <p class="mb-4">Encontramos <?php echo $totalProducts; ?> produto(s) para a sua pesquisa.</p>

            <div class="grid grid-cols-3 gap-6">
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <div class="border p-4 rounded-md">
                        <img src="images/<?php echo $row['imagem']; ?>" alt="<?php echo $row['nome']; ?>" class="w-full h-40 object-cover rounded-md mb-4">
                        <h3 class="text-lg font-semibold"><?php echo $row['nome']; ?></h3>
                        <p class="text-gray-700"><?php echo 'R$ ' . number_format($row['preco'], 2, ',', '.'); ?></p>
                        <a href="produto.php?id=<?php echo $row['id']; ?>" class="text-yellow-500 hover:text-yellow-400">Ver detalhes</a>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>

</body>
</html>
