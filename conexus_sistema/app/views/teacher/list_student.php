<?php
require_once "../../models/aluno.php";

$alunoModel = new Aluno();
$itens = $alunoModel->listarTodos(); // Ou filtrar por turma, se preferir
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Presença</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="stylesheet" href="../../../public/css/teacher_style.css">
</head>
<body>

<?php include '../components/teacher_header.php'; ?>

<div class="box-container-list">
    <div class="flex-between heading-bar">
        <h1 class="heading">Registro de Presença</h1>
    </div>

    <?php if (!empty($itens)): ?>
    <form action="presence.php" method="POST">
        <div class="table-responsive custom-table">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-header">
                    <tr>
                        <th>Matricula</th>
                        <th>Nome</th>
                        <th>Falta</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($itens as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['idaluno']) ?></td>
                            <td><?= htmlspecialchars($item['nome']) ?></td>
                            <td>
                                <input type="checkbox" name="faltas[]" value="<?= htmlspecialchars($item['idaluno']) ?>">
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div style="margin-top: 20px;">
            <button type="submit" class="inline-btn">Salvar</button>
        </div>
    </form>
    <?php else: ?>
        <div class="empty">Nenhum estudante encontrado.</div>
    <?php endif; ?>
</div>

<script src="../../../public/js/admin_script.js"></script>
</body>
</html>

