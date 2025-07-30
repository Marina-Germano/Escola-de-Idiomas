<?php
session_start();

require_once __DIR__ . '/../../models/aluno_turma.php';

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
$alunos = $alunoTurmaModel->listarTodos($idturma); // lista alunos da turma
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Registrar Presença - Turma <?= htmlspecialchars($idturma) ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" />
    <link rel="stylesheet" href="../../../public/css/teacher_style.css" />
</head>
<body>
<?php include '../components/teacher_header.php'; ?>

<div class="box-container-list">
    <div class="flex-between heading-bar">
        <h1 class="heading">Registrar Presença - Turma <?= htmlspecialchars($idturma) ?></h1>
        <div class="action-links">
            <a href="list_class.php" class="inline-btn">Minhas Turmas</a>
        </div>
    </div>

    <section class="box-container-list">
        <form method="POST" action="../../controllers/presencaController.php?acao=registrar" class="form-box">
            <input type="hidden" name="idturma" value="<?= htmlspecialchars($idturma) ?>" />

            <div class="form-grid full-span">
                <?php if (empty($alunos)): ?>
                    <p>Nenhum aluno encontrado nesta turma.</p>
                <?php else: ?>
                    <table class="styled-table">
                        <thead class="table-header">
                            <tr>
                                <th>Nome do Aluno</th>
                                <th>Faltou?</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($alunos as $aluno): ?>
                                <tr>
                                    <td><?= htmlspecialchars($aluno['nome_aluno']) ?></td>
                                    <td style="text-align: center;">
                                        <input type="checkbox" name="faltas[]" value="<?= htmlspecialchars($aluno['idaluno_turma']) ?>" />
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
            <div class="text-end" style="justify-content: flex-end; margin-top: 20px;">
                <div class="form-actions" style="display: flex; gap: 10px;">
                    <button type="submit" class="btn">Salvar Presenças</button>
                    <button type="button" onclick="marcarTodosFaltaram(false)" class="btn">Desmarcar Todos</button>
                </div>
            </div>
        </form>
    </section>
</div>

<script src="../../../public/js/admin_script.js">
    function marcarTodosFaltaram(marcar) {
        const checkboxes = document.querySelectorAll('input[name="faltas[]"]');
        checkboxes.forEach(cb => cb.checked = marcar);
    }
</script>
</body>
</html>
