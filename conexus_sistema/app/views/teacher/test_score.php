<?php
session_start();

require_once __DIR__ . '/../../models/aluno_turma.php';
require_once __DIR__ . '/../../models/avaliacao.php';

// Proteção: só professor pode acessar
if (!isset($_SESSION['idusuario']) || $_SESSION['papel'] !== 'professor') {
    header('Location: /conexus_sistema/app/views/login.php');
    exit;
}

$idfuncionario = $_SESSION['idusuario'];
$idturma = $_GET['idturma'] ?? null;

if (!$idturma) {
    echo "Turma não especificada.";
    exit;
}

$alunoTurmaModel = new AlunoTurma();
$alunos = $alunoTurmaModel->listarTodos($idturma); // deve trazer idaluno_turma + nome_aluno
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Registrar Notas - Turma <?= htmlspecialchars($idturma) ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" />
    <link rel="stylesheet" href="../../../public/css/teacher_style.css" />
</head>
<body>
<?php include '../components/teacher_header.php'; ?>

<div class="box-container-list">
    <div class="flex-between heading-bar">
        <h1 class="heading">Registrar Nota - Turma <?= htmlspecialchars($idturma) ?></h1>
        <div class="action-links">
            <a href="list_class.php" class="inline-btn">Minhas Turmas</a>
        </div>
    </div>

    <section class="box-container-list">
        <form method="POST" action="../../controllers/avaliacaoController.php?acao=registrar" class="form-box">
            <input type="hidden" name="idturma" value="<?= htmlspecialchars($idturma) ?>">
            <input type="hidden" name="idfuncionario" value="<?= htmlspecialchars($idfuncionario) ?>">

            <div class="form-grid full-span">
                <?php if (empty($alunos)): ?>
                    <p>Nenhum aluno encontrado nesta turma.</p>
                <?php else: ?>
                    <table class="styled-table">
                        <thead class="table-header">
                            <tr>
                                <th>Nome do Aluno</th>
                                <th>Nota</th>
                                <th>Peso</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($alunos as $index => $aluno): ?>
                                <tr>
                                    <td>
                                        <?= htmlspecialchars($aluno['nome_aluno']) ?>
                                        <input type="hidden" name="idaluno_turma[]" value="<?= $aluno['idaluno_turma'] ?>">
                                    </td>
                                    <td style="text-align: center;">
                                        <input type="number" name="nota[]" step="0.01" min="0" max="10" required>
                                    </td>
                                    <td style="text-align: center;">
                                        <input type="number" name="peso[]" step="0.01" min="0" value="1.0" required>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>

            <div class="text-end" style="justify-content: flex-end; margin-top: 20px;">
                <div class="form-actions" style="display: flex; gap: 10px;">
                    <button type="submit" class="btn">Salvar Notas</button>
                </div>
            </div>
        </form>
    </section>
</div>

<script src="../../../public/js/admin_script.js"></script>
</body>
</html>
