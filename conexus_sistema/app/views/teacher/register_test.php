<?php
require_once __DIR__ . '/../../models/turma.php';
require_once __DIR__ . '/../../models/avaliacao.php';
require_once __DIR__ . '/../../models/aluno_turma.php';

$turmaModel = new Turma();
$avaliacaoModel = new Avaliacao();
$alunoTurmaModel = new AlunoTurma();

$turmas = $turmaModel->listarTodos();

$turmaSelecionada = $_GET['turma'] ?? null;
$alunosTurma = [];

if ($turmaSelecionada) {
    $alunosTurma = $alunoTurmaModel->listarTodos($turmaSelecionada); // Deve trazer aluno_turma com nome do aluno
}

$modoEdicao = false;
$item = [];

if (isset($_GET['acao']) && $_GET['acao'] === 'editar' && isset($_GET['id'])) {
    $modoEdicao = true;
    $item = $avaliacaoModel->listarId($_GET['id']);
    // Se em modo edição, forçar turma selecionada baseada no aluno da avaliação
    $turmaSelecionada = $item['idturma'] ?? $turmaSelecionada;
    $alunosTurma = $alunoTurmaModel->listarTodos($turmaSelecionada);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Conexus - Registro de Avaliação</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="stylesheet" href="../../../public/css/teacher_style.css">
</head>
<body>

<?php include '../components/teacher_header.php'; ?>

<section class="form-container">

    <!-- Seletor de Turma antes do formulário -->
    <form method="GET" action="">
        <p><strong>Selecionar Turma</strong></p>
        <select name="turma" class="box" onchange="this.form.submit()">
            <option value="">Selecione uma turma</option>
            <?php foreach ($turmas as $turma): ?>
                <option value="<?= $turma['idturma'] ?>" <?= ($turmaSelecionada == $turma['idturma']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($turma['descricao']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>

    <!-- Formulário principal -->
    <form action="../../controllers/avaliacaoController.php?acao=<?= $modoEdicao ? 'alterar' : 'cadastrar' ?>" method="POST">
        <h2 style="margin-bottom: 20px;"><?= $modoEdicao ? 'Editar Avaliação' : 'Cadastrar Avaliação' ?></h2>

        <?php if ($modoEdicao): ?>
            <input type="hidden" name="idavaliacao" value="<?= $item['idavaliacao'] ?>">
        <?php endif; ?>

        <div class="flex">
            <div class="col">
                <p><strong>Aluno da Turma <span>*</span></strong></p>
                <select name="idaluno_turma" class="box" required>
                    <option value="">Selecione um aluno</option>
                    <?php foreach ($alunosTurma as $aluno): ?>
                        <option value="<?= $aluno['idaluno_turma'] ?>"
                            <?= ($modoEdicao && $item['idaluno_turma'] == $aluno['idaluno_turma']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($aluno['nome_aluno']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <p><strong>Título</strong></p>
                <input type="text" name="titulo" class="box" maxlength="255" placeholder="Título da avaliação"
                       value="<?= $modoEdicao ? htmlspecialchars($item['titulo']) : '' ?>">

                <p><strong>Descrição <span>*</span></strong></p>
                <input type="text" name="descricao" class="box" required maxlength="255" placeholder="Descrição da avaliação"
                       value="<?= $modoEdicao ? htmlspecialchars($item['descricao']) : '' ?>">

                <p><strong>Data da Avaliação <span>*</span></strong></p>
                <input type="date" name="data_avaliacao" class="box" required
                       value="<?= $modoEdicao ? $item['data_avaliacao'] : '' ?>">
            </div>

            <div class="col">
                <p><strong>Nota</strong></p>
                <input type="number" name="nota" class="box" step="0.01" min="0" max="10"
                       value="<?= $modoEdicao ? $item['nota'] : '' ?>">

                <p><strong>Peso</strong></p>
                <input type="number" name="peso" class="box" step="0.01" min="0"
                       value="<?= $modoEdicao ? $item['peso'] : '1.0' ?>">

                <p><strong>Observações</strong></p>
                <textarea name="observacao" class="box" rows="4"><?= $modoEdicao ? htmlspecialchars($item['observacao']) : '' ?></textarea>

                <input type="hidden" name="idfuncionario" value="<?= $_SESSION['idusuario'] ?? '' ?>">
            </div>
        </div>

        <input type="submit" value="<?= $modoEdicao ? 'Atualizar' : 'Cadastrar' ?>" class="btn">
    </form>
</section>

<script src="../../../public/js/admin_script.js"></script>
</body>
</html>
