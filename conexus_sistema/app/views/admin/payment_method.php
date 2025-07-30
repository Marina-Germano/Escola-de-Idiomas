<?php

require_once "../../models/aluno.php";

$alunoModel = new Aluno();
$itens = $alunoModel->listarTodos(); // ou outro nome apropriado para seu método
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estudante</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="stylesheet" href="../../../public/css/admin_style.css">
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<div class="box-container-list">
    <div class="flex-between heading-bar">
        <h1 class="heading">Adicionar pagamento</h1>
    </div>

    <?php if (!empty($itens)): ?>
        <div class="table-responsive custom-table">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-header">
                    <tr>
                        <th>Nome</th>
                        <th>CPF</th>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($itens as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['nome']) ?></td>
                            <td><?= htmlspecialchars($item['cpf']) ?></td>
                            <td class="text-end">
                                <a href="/escola-de-idiomas/conexus_sistema/app/views/admin/register_payment.php?idaluno=<?= $item['idaluno'] ?>"
                                class="inline-option-btn"></i> Adicionar Pagamento</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="empty">Nenhum estudante cadastrado.</div>
    <?php endif; ?>
</div>

<script src="../../../public/js/admin_script.js"></script>
</body>
</html>