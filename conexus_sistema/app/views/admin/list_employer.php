<?php
require_once "../../models/funcionario.php";
$funcionarioModel = new Funcionario();
$itens = $funcionarioModel->listarTodos(); // ou outro nome apropriado para seu método
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

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3">Funcionários</h1>
        <a href="register_employer.php" class="btn btn-primary"><i class="fas fa-plus"></i> Novo Funcionário</a>
    </div>

    <?php if (!empty($itens)): ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Nome</th>
                        <th>CPF</th>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($itens as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['nome']); ?></td>
                            <td><?php echo htmlspecialchars($item['cpf']); ?></td>
                            <td class="text-end">
                                <a href="register_employer.php?acao=editar&id=<?= $item['idfuncionario'] ?>" class="btn btn-sm btn-warning">Editar</a>
                                    <i class="fas fa-edit"></i> Editar</a>

                                <a href="register_employer.php?acao=excluir&idfuncionario=<?= $item['idfuncionario'] ?>" class="delete-btn"
                                onclick="return confirm('Tem certeza que deseja excluir este funcionário?');">
                                    <i class="fas fa-trash-alt"></i> Excluir</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-info">
            Nenhum funcionário cadastrado.
        </div>
    <?php endif; ?>
</div>