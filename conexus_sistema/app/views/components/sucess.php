<?php

if (!isset($_SESSION['idusuario'])) {
    header("Location: ../login.php");
    exit;
}

$mensagem = "Operação realizada com sucesso!";
if (isset($_GET['cadastro']) && $_GET['cadastro'] === 'ok') {
    $mensagem = "Material cadastrado com sucesso!";

} elseif (isset($_GET['alterar']) && $_GET['alterar'] === 'ok') {
    $mensagem = "Operação realizada com sucesso!";

} elseif (isset($_GET['excluir']) && $_GET['excluir'] === 'ok') {
    $mensagem = "Operação realizada com sucesso!";
}

if (isset($_GET['presenca']) && $_GET['presenca'] === 'ok') {
    $mensagem = "Operação realizada com sucesso!";
}
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Sucesso</title>
    <link rel="stylesheet" href="../../../public/css/login_style.css"> <!-- ajusta o caminho conforme sua estrutura -->
    <link rel="stylesheet" href="../../../public/css/success_style.css"> <!-- ajusta o caminho conforme sua estrutura -->
</head>
<body>
    <div class="form-container">
        <div class="message form">
            <?= htmlspecialchars($mensagem) ?><br>
            <?php if ($_SESSION['papel'] === 'admin'): ?>
                <a href="../admin/dashboard.php">Ir para a Home</a>
            <?php else: ?>
                <a href="../teacher/home.php">Ir para a Home</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="container">
        <h1><?= htmlspecialchars($mensagem) ?></h1>
        <?php if ($_SESSION['papel'] === 'admin' ): ?>
        <a href="../admin/dashboard.php">Home</a>
        <?php else : ?>
        <a href="../teacher/home.php">Home</a>
        <?php endif; ?>
    </div>
</body>
</html>