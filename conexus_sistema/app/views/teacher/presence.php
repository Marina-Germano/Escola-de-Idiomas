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
    <link rel="stylesheet" href="../../../public/css/admin_style.css" />
</head>
<body>
<?php include '../components/teacher_header.php'; ?>

<h2>Registrar Presença - Turma <?= htmlspecialchars($idturma) ?></h2>

<form method="POST" action="../../controllers/presenca.php?acao=registrar">
    <input type="hidden" name="idturma" value="<?= htmlspecialchars($idturma) ?>" />

    <table>
        <thead>
            <tr>
                <th>Aluno</th>
                <th>Faltou?</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($alunos as $aluno): ?>
                <tr>
                    <td><?= htmlspecialchars($aluno['nome_aluno']) ?></td>
                    <td>
                        <!-- Checkbox: se marcado, aluno está ausente -->
                        <input type="checkbox" name="faltas[]" value="<?= htmlspecialchars($aluno['idaluno_turma']) ?>" />
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <button type="submit" class="btn">Salvar Presenças</button>
</form>

<script src="../../../public/js/admin_script.js"></script>
</body>
</html>
