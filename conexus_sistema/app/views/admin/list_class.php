<?php
require_once "../../models/turma.php";
$turmaModel = new Turma();
$itens = $turmaModel->listarTurma();
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estudantes</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="stylesheet" href="../../../public/css/admin_style.css">
</head>
<body>

<?php include '../components/admin_header.php'; ?>


<div class="box-container-list">
    <div class="flex-between heading-bar">
        <h1 class="heading">Turmas</h1>
        <a href="register_class.php" class="inline-btn"><i class="fas fa-plus"></i> Nova Turma</a>
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
                                <a href="register_class.php?acao=editar&id=<?= $item['idturma'] ?>" class="inline-option-btn">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <a href="../../controllers/turmaController.php?acao=excluir&id=<?= $item['idturma'] ?>" class="inline-delete-btn"
                                onclick="return confirm('Tem certeza que deseja excluir esta turma?');">
                                    <i class="fas fa-trash-alt"></i> Excluir
                                </a>
                                <a href="student_class.php?idturma=<?= $item['idturma'] ?>" 
                                class="inline-btn">
                                    <i class="fa-solid fa-user-plus"></i> Adicionar Alunos
                                </a>
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

