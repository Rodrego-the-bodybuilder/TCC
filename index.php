<?php
session_start();
include("./conexao.php")// Conexão com o banco de dados

if(isset($_POST['nome']) || (isset($_POST['email']) || isset($_POST['senha']))) {
    if(strlen($_POST['nome']) == 0) {
        echo "Preencha seu nome";
    }else if(strlen($_POST['email']) == 0) {
        echo "Preencha seu e-mail";
    } else if(strlen($_POST['senha']) == 0) {
        echo "Preencha sua senha";
    } else {
        $email = $servidor->real_scape_string($_POST['nome']);
        $email = $servidor->real_scape_string($_POST['email']);
        $email = $servidor->real_scape_string($_POST['senha']);

        $sql_code = "SELECT * FROM usuarios where email = '$email' AND senha = '$senha'";
        $sql_query = $servidor->($sql_code) or die("Falha na execução do código SQL: " . $servidor->error);

        $quantidade = $sql_query->num_rows;
        if(quantidade == 1) {
            $usuario = $sql_query->fetch_assoc();

            if(!isset($_SESSION)){
                session_start();
            }

            $_SESSION ['id'] = $usuario['id'];
            $_SESSION ['nome'] = $usuario['nome'];
        }else {
            echo "Falha ao logar! Dados incorretos";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Página Inicial</title>
</head>
<body>
    <div class="container">
        <header>
            <h1>Bem-vindo à Página Inicial</h1>
        </header>

        <?php if ($isAdmin): ?>
            <!-- Formulário para adicionar produtos (apenas administradores) -->
            <h2>Adicionar Produto</h2>
            <form method="POST" action="index.php">
                <input type="text" name="nome" placeholder="Nome do Produto" required>
                <textarea name="descricao" placeholder="Descrição do Produto" required></textarea>
                <input type="number" step="0.01" name="preco" placeholder="Preço do Produto" required>
                <button type="submit">Adicionar Produto</button>
            </form>
        <?php endif; ?>

        <!-- Exibe produtos cadastrados -->
        <h2>Produtos</h2>
        <div class="product-list">
            <ul>
                <?php
                $query = "SELECT * FROM products";
                $result = $conn->query($query);
                while ($produto = $result->fetch_assoc()) {
                    echo "<li>
                            <h3>{$produto['nome']} - R$ {$produto['preco']}</h3>
                            <p>{$produto['descricao']}</p>
                          </li>";
                }
                ?>
            </ul>
        </div>

        <!-- Link de logout -->
        <a class="logout" href="logout.php">Sair</a>
    </div>
</body>
</html>
