<!-- views/admin/editar_material.php -->
<?php
require_once __DIR__ . '/../../models/material.php';
require_once __DIR__ . '/../../models/tipo_material.php';
require_once __DIR__ . '/../../models/nivel.php';
require_once __DIR__ . '/../../models/idioma.php';
require_once __DIR__ . '/../../models/turma.php';

$materialModel = new Material();
$material = $materialModel->listarId($_GET['idmaterial']);

$idiomaModel = new Idioma();
$nivelModel = new Nivel();
$tipoModel = new TipoMaterial();
$turmaModel = new Turma();

$idiomas = $idiomaModel->listarTodos();
$nivels = $nivelModel->listarTodos();
$tipos = $tipoModel->listarTodos();
$turmas = $turmaModel->listarTodos();
?>

<h2>Editar Material</h2>
<form action="../../controllers/materialController.php?acao=alterar" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="idmaterial" value="<?= $material['idmaterial'] ?>">

    <label>Título:</label>
    <input type="text" name="titulo" value="<?= $material['titulo'] ?>" required><br>

    <label>Descrição:</label>
    <textarea name="descricao"><?= $material['descricao'] ?></textarea><br>

    <label>Quantidade:</label>
    <input type="number" name="quantidade" value="<?= $material['quantidade'] ?>" required><br>

    <label>Idioma:</label>
    <select name="ididioma" required>
        <?php foreach ($idiomas as $i): ?>
            <option value="<?= $i['ididioma'] ?>" <?= $i['ididioma'] == $material['ididioma'] ? 'selected' : '' ?>><?= $i['descricao'] ?></option>
        <?php endforeach; ?>
    </select><br>

    <label>Nível:</label>
    <select name="idnivel" required>
        <?php foreach ($nivels as $n): ?>
            <option value="<?= $n['idnivel'] ?>" <?= $n['idnivel'] == $material['idnivel'] ? 'selected' : '' ?>><?= $n['descricao'] ?></option>
        <?php endforeach; ?>
    </select><br>

    <label>Tipo de Material:</label>
    <select name="idtipo_material" required>
        <?php foreach ($tipos as $t): ?>
            <option value="<?= $t['idtipo_material'] ?>" <?= $t['idtipo_material'] == $material['idtipo_material'] ? 'selected' : '' ?>><?= $t['descricao'] ?></option>
        <?php endforeach; ?>
    </select><br>

    <label>Turma:</label>
    <select name="idturma" required>
        <?php foreach ($turmas as $turma): ?>
            <option value="<?= $turma['idturma'] ?>" <?= $turma['idturma'] == $material['idturma'] ? 'selected' : '' ?>><?= $turma['descricao_turma'] ?></option>
        <?php endforeach; ?>
    </select><br>

    <label>ID Professor:</label>
    <input type="number" name="idprofessor" value="<?= $material['idprofessor'] ?>" required><br>

    <label>Novo Arquivo (opcional):</label>
    <input type="file" name="arquivo"><br><br>

    <button type="submit">Salvar Alterações</button>
</form>
<a href="list_material.php">Voltar à lista</a>
