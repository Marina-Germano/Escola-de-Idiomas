<?php
require_once "../../models/turma.php";
require_once "../../models/aluno_turma.php";
require_once "../../models/presenca.php";

$acao = $_GET['acao'] ?? '';
$idturma = $_GET['id'] ?? '';

if ($acao === 'listar' && $idturma) {
    // Listar alunos da turma específica
    $alunoTurmaModel = new AlunoTurma();

    $alunos = $alunoTurmaModel->listarTodos($idturma); // Função nova que vamos criar

} else {
    // Listar todas as turmas (comportamento padrão)
    $turmaModel = new Turma();
    $itens = $turmaModel->listarTurma();
}
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Turmas</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="stylesheet" href="../../../public/css/teacher_style.css">
</head>
<body>

<?php include '../components/teacher_header.php'; ?>


<div class="box-container-list">
    <div class="flex-between heading-bar">
        <h1 class="heading">Turmas</h1>
    </div>

    <?php if (!empty($itens)): ?>
        <div class="table-responsive custom-table">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-header">
                    <tr>
                        <th>Sala</th>
                        <th>Idioma</th>
                        <th>Nível</th>
                        <th>Dias da semana</th>
                        <th>Horário</th>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($itens as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['sala'] ?? 'sem sala'); ?></td>
                            <td><?php echo htmlspecialchars($item['idioma'] ?? 'sem idioma'); ?></td>
                            <td><?php echo htmlspecialchars($item['nivel'] ?? 'sem nível'); ?></td>
                            <td><?php echo htmlspecialchars($item['dias_semana'] ?? 'sem dias'); ?></td>
                            <td><?php echo htmlspecialchars($item['hora_inicio'] ?? 'sem horário'); ?></td></td>
                            <td class="text-end">

                                <a href="presence.php?acao=listarAlunos&idturma=<?=$item['idturma'] ?>" class="btn btn-secondary">
                                    <i class="fas fa-edit"></i>Marcar Presença</a>

                                <a href="test_score.php?acao=listarAlunos&idturma=<?=$item['idturma'] ?>" class="btn btn-secondary">
                                    <i class="fa-solid fa-award"></i>Publicar Notas</a>
                                    

                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="empty">Nenhuma turma cadastrada.</div>
    <?php endif; ?>
</div>


<script src="../../../public/js/admin_script.js"></script>
</body>
</html>