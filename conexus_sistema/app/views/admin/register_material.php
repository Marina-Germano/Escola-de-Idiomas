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

<?php include '../components/admin_header.php';?>

<?php
require_once(__DIR__ . '/../../config/conexao.php');
require_once(__DIR__ . '/../../models/idioma.php');
require_once(__DIR__ . '/../../models/nivel.php');
require_once(__DIR__ . '/../../models/tipo_material.php');
require_once(__DIR__ . '/../../models/material.php');
require_once(__DIR__ . '/../../models/turma.php');

$conn = Conexao::conectar();
$materialModel = new Material();

$idiomaModel = new Idioma();
$itens = $idiomaModel->listarTodos();

$nivelModel = new Nivel();
$niveis = $nivelModel->listarTodos();

$tipoMaterialModel = new TipoMaterial();
$tiposMateriais = $tipoMaterialModel->listarTodos();

$turmaModel = new Turma();
$turmas = $turmaModel->listarTodos();

$modoEdicao = false;
$item = [];

if (isset($_GET['acao']) && $_GET['acao'] === 'editar' && isset($_GET['id'])) {
    $modoEdicao = true;
    $item = $funcionarioModel->listarId($_GET['id']);
}
?>


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
                    <option value="" selected>Selecionar Tipo de Material</option>
                    <?php foreach ($tiposMateriais as $tipo): ?>
                        <option value="<?= $tipo['idtipo_material'] ?>"><?= htmlspecialchars($tipo['descricao']) ?></option>
                    <?php endforeach; ?>
                </select>

                <p><strong>Idioma</strong></p>
                <select name="ididioma" class="box">
                    <option value="" selected>Selecionar Idioma</option>
                    <?php foreach ($itens as $idioma): ?>
                        <option value="<?= $idioma['ididioma'] ?>"><?= htmlspecialchars($idioma['descricao']) ?></option>
                    <?php endforeach; ?></select>


                <p><strong>Nível</strong></p>
                <select name="idnivel" class="box">
                    <option value="" selected>Selecionar Nível</option>
                    <?php foreach ($niveis as $nivel): ?>
                        <option value="<?= $nivel['idnivel'] ?>"><?= htmlspecialchars($nivel['descricao']) ?></option>
                    <?php endforeach; ?>
                </select>
            
                <p><strong>Arquivo</strong></p>
                <input type="file" name="arquivo" accept=".pdf,.doc,.docx,.mp4,.jpg,.png" required class="box">

                <p><strong>ID do Professor <span>*</span></strong></p>
                <input type="number" name="idprofessor" placeholder="ID do professor responsável" class="box">

                <p><strong>Descrição do Material</strong></p>
                <input type="text" name="descricao_material" class="box" placeholder="Descrição do material">

            </div>

            <div class="col">

                <p><strong>Turma</strong></p>
                <select name="idturma" class="box">
                    <option value="" selected>Selecionar Turma</option>
                    <?php foreach ($turmas as $turma): ?>
                        <option value="<?= $turma['idturma'] ?>"><?= htmlspecialchars($turma['descricao']) ?></option>
                    <?php endforeach; ?></select>

                <input type="text" name="descricao_turma" class="box" placeholder="Descrição da nova turma">
                <input type="text" name="dias_semana" class="box" placeholder="Dias da semana (ex: Seg, Qua)">
                <input type="time" name="hora_inicio" class="box" placeholder="Hora de início">
                <input type="number" name="capacidade_maxima" class="box" placeholder="Capacidade máxima">
                <input type="text" name="sala" class="box" placeholder="Sala da turma">
                <input type="text" name="tipo_recorrencia" class="box" placeholder="Tipo de recorrência (ex: semanal)">
            
                <p><strong>Quantidade <span>*</span></strong></p>
                <input type="number" name="quantidade" min="1" required class="box">

            </div>
        </div>

        <input type="submit" value="Cadastrar Material Completo" class="btn">

    </form>
</section>

<script src="../../../public/js/admin_script.js"></script>

</body>
</html>
