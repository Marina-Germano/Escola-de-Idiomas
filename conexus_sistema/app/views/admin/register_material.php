<!-- views/admin/register_material.php -->
<?php
require_once __DIR__ . '/../../models/idioma.php';
require_once __DIR__ . '/../../models/nivel.php';
require_once __DIR__ . '/../../models/tipo_material.php';
require_once __DIR__ . '/../../models/turma.php';

$idiomaModel = new Idioma();
$nivelModel = new Nivel();
$tipoMaterialModel = new TipoMaterial();
$turmaModel = new Turma();

$idiomas = $idiomaModel->listarTodos();
$nivels = $nivelModel->listarTodos();
$tipos = $tipoMaterialModel->listarTodos();
$turmas = $turmaModel->listarTodos();
?>

<h2>Cadastrar Novo Material</h2>
<form action="../../controllers/materialController.php?acao=cadastrar" method="POST" enctype="multipart/form-data">
    <label>Título:</label>
    <input type="text" name="titulo" required><br>

    <label>Descrição:</label>
    <textarea name="descricao_material" required></textarea><br>

    <label>Quantidade:</label>
    <input type="number" name="quantidade" required><br>

    <!-- <label>Formato do Arquivo:</label>
    <input type="text" name="formato_arquivo" required><br> -->

    <label>Idioma:</label>
    <select name="descricao_idioma" required>
        <?php foreach ($idiomas as $i): ?>
            <option value="<?= $i['descricao'] ?>"><?= $i['descricao'] ?></option>
        <?php endforeach; ?>
    </select><br>

    <label>Nível:</label>
    <select name="descricao_nivel" required>
        <?php foreach ($nivels as $n): ?>
            <option value="<?= $n['descricao'] ?>"><?= $n['descricao'] ?></option>
        <?php endforeach; ?>
    </select><br>

    <label>Tipo de Material:</label>
    <select name="descricao_tipo_material" required>
        <?php foreach ($tipos as $t): ?>
            <option value="<?= $t['descricao'] ?>"><?= $t['descricao'] ?></option>
        <?php endforeach; ?>
    </select><br>

    <label>Turma (para cadastrar nova):</label><br>
    <input type="text" name="descricao_turma" placeholder="Descrição da turma" required><br>
    <input type="text" name="dias_semana" placeholder="Dias da semana" required><br>
    <input type="time" name="hora_inicio" required><br>
    <input type="number" name="capacidade_maxima" placeholder="Capacidade" required><br>
    <input type="text" name="sala" placeholder="Sala" required><br>
    <input type="text" name="tipo_recorrencia" placeholder="Tipo de recorrência"><br>
    <input type="number" name="idprofessor" placeholder="ID do professor" required><br>

    <label>Arquivo:</label>
    <input type="file" name="arquivo"><br><br>

    <button type="submit">Cadastrar</button>
</form>
<a href="listar.php">Voltar à lista</a>
