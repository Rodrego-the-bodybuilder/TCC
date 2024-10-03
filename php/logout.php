<?php
session_start();

// Destrói todas as informações da sessão
$_SESSION = array(); // Limpa a sessão
session_destroy(); // Destrói a sessão

// Redireciona para a página de login
header("Location: login.php");
exit();
?>