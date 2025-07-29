<?php
require_once "../../models/avaliacao.php";
$avaliacaoModel = new Avaliacao();
$itens = $avaliacaoModel->listarTodos(); // ou outro nome apropriado para seu método

?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avaliação</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="stylesheet" href="../../../public/css/teacher_style.css">
</head>
<body>

<?php include '../components/teacher_header.php'; ?>

<div class="box-container-list">
    <div class="flex-between heading-bar">
        <h1 class="heading">Avaliação</h1>
        <a href="register_avaliacao.php" class="inline-btn"><i class="fas fa-plus"></i>Adicionar avaliacao</a>
    </div>

    <?php if (!empty($itens)): ?>
        <div class="table-responsive custom-table">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-header">
                    <tr>
                        <th>Avaliação</th>
                        <th>Inserir nota</th>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($itens as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['descricao']) ?></td>
                            <td class="text-end">
                                <a href="register_avaliacao.php?acao=editar&id=<?= $item['idavaliacao'] ?>" class="inline-option-btn">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <a href="../../controllers/avaliacaoController.php?acao=excluir&id=<?= $item['idavaliacao'] ?>" class="inline-delete-btn"
                                onclick="return confirm('Tem certeza que deseja excluir este avaliacao?');">
                                    <i class="fas fa-trash-alt"></i> Excluir
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="empty">Nenhum funcionário cadastrado.</div>
    <?php endif; ?>
</div>


<script src="../../../public/js/admin_script.js"></script>
</body>
</html>