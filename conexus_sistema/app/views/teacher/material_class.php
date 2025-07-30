<?php
require_once "../../models/turma.php";
require_once "../../models/material.php";

$turmaModel = new Turma();
$materialModel = new Material();

$turmas = $turmaModel->listarTurma();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Materiais da Turma</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="stylesheet" href="../../../public/css/teacher_style.css">
</head>
<body>

<?php include '../components/teacher_header.php'; ?>

<div class="box-container-list">
    <div class="flex-between heading-bar">
        <h1 class="heading">Materiais da Turma</h1>
    </div>

    <?php if (!empty($turmas)): ?>
        <div class="table-responsive custom-table">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-header">
                    <tr>
                        <th>Idioma</th>
                        <th>Nível</th>
                        <th>Dias da semana</th>
                        <th>Horário</th>
                        <th>Materiais vinculados</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($turmas as $turma): ?>
                        <tr>
                            <td><?= htmlspecialchars($turma['idioma'] ?? 'sem idioma') ?></td>
                            <td><?= htmlspecialchars($turma['nivel'] ?? 'sem nível') ?></td>
                            <td><?= htmlspecialchars($turma['dias_semana'] ?? 'sem dias') ?></td>
                            <td><?= htmlspecialchars($turma['hora_inicio'] ?? 'sem horário') ?></td>
                            
                            <!-- Materiais vinculados a essa turma -->
                            <td>
                                <?php
                                    $materiaisVinculados = $materialModel->listarPorTurma($turma['idturma']);
                                    if (!empty($materiaisVinculados)) {
                                        foreach ($materiaisVinculados as $m) {
                                            echo htmlspecialchars($m['titulo']) . "<br>";
                                        }
                                    } else {
                                        echo 'Nenhum';
                                    }
                                ?>
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
