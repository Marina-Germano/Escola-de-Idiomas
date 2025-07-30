<?php
session_start();

// Limpa todas as variáveis de sessão
session_unset();

// Destroi a sessão
session_destroy();

// Impede cache do navegador (opcional, mas recomendado)
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 1 Jan 2000 00:00:00 GMT");

// Redireciona para a tela de login
header('Location:/escola-de-idiomas/conexus_sistema/app/views/login.php');
exit;
?>
