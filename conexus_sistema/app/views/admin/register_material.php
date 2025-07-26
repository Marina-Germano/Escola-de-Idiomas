<?php
require_once __DIR__ . '/../../models/idioma.php';
require_once __DIR__ . '/../../models/nivel.php';
require_once __DIR__ . '/../../models/tipo_material.php';
require_once __DIR__ . '/../../models/turma.php';
require_once __DIR__ .'/../../models/material.php';

$idiomaModel = new Idioma();
$nivelModel = new Nivel();
$tipoMaterialModel = new TipoMaterial();
$turmaModel = new Turma();
$materialModel = new Material();

$idiomas = $idiomaModel->listarTodos();
$nivels = $nivelModel->listarTodos();
$tipos = $tipoMaterialModel->listarTodos();
$turmas = $turmaModel->listarTodos();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conexus - Cadastrar Materiais</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="stylesheet" href="../../../public/css/admin_style.css">
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="form-container">

    <form action="../../controllers/materialController.php?acao=cadastrar"
    method="POST" enctype="multipart/form-data">
        <!-- <h2 style="margin-bottom: 20px;">Cadastro Completo de Material</h2> -->

        <div class="flex">
            <div class="col">

                <p><strong>Título <span>*</span></strong></p>
                <input type="text" name="titulo" maxlength="255" required placeholder="Título do material" class="box">

                <p><strong>Tipo de Material</strong></p>
                <select name="idtipo_material" class="box">
                    <?php foreach ($tipos as $t): ?>
            <option value="<?= $t['descricao'] ?>"><?= $t['descricao'] ?></option>
        <?php endforeach; ?>
                    </select>

                <p><strong>Idioma</strong></p>
                <select name="ididioma" class="box">
                    <?php foreach ($idiomas as $i): ?>
            <option value="<?= $i['descricao'] ?>"><?= $i['descricao'] ?></option>
        <?php endforeach; ?>
    </select><br>

                <p><strong>Nível</strong></p>
                <select name="idnivel" class="box">
                    <?php foreach ($nivels as $n): ?>
            <option value="<?= $n['descricao'] ?>"><?= $n['descricao'] ?></option>
        <?php endforeach; ?>
                </select>
            </div>

            <div class="col">
                <p><strong>Arquivo </strong></p>
                <input type="file" name="arquivo" accept=".pdf,.doc,.docx,.mp4,.jpg,.png" class="box">

                <p><strong>Descrição do Material</strong></p>
                <textarea name="descricao_material" class="box" rows="5" placeholder="Descrição do material"></textarea>

                <p><strong>Quantidade <span>*</span></strong></p>
                <input type="number" name="quantidade" min="1" required class="box">

            </div>
        </div>

        <input type="submit" value="Cadastrar" class="btn">

    </form>
</section>

<script src="../../../public/js/admin_script.js"></script>

</body>
</html>
