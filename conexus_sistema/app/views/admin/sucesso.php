<?php
session_start();

if (!isset($_SESSION['idusuario'])) {
    header("Location: ../login.php");
    exit;
}

$mensagem = "Operação realizada com sucesso!";
if (isset($_GET['cadastro']) && $_GET['cadastro'] === 'ok') {
    $mensagem = "Material cadastrado com sucesso!";
} elseif (isset($_GET['editar']) && $_GET['editar'] === 'ok') {
    $mensagem = "Material alterado com sucesso!";
} elseif (isset($_GET['excluir']) && $_GET['excluir'] === 'ok') {
    $mensagem = "Material excluído com sucesso!";
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Sucesso</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: #fff;
            border: 2px solid #4CAF50;
            border-radius: 10px;
            padding: 40px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .container h1 {
            color: #4CAF50;
        }
        .container a {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 24px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .container a:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><?= htmlspecialchars($mensagem) ?></h1>
        <a href="dashboard.php">Home</a>
    </div>
</body>
</html>
