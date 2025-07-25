<!-- views/admin/listar.php -->
<?php
require_once __DIR__ . '/../../models/material.php';

$materialModel = new Material();
$materiais = $materialModel->listarTodos();
?>

<h2>Lista de Materiais</h2>
<a href="register_material.php">➕ Adicionar Novo Material</a>
<table border="1" cellpadding="5">
    <thead>
        <tr>
            <th>Título</th>
            <th>Descrição</th>
            <th>Idioma</th>
            <th>Nível</th>
            <th>Tipo</th>
            <th>Arquivo</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($materiais as $m): ?>
            <tr>
                <td><?= htmlspecialchars($m['titulo']) ?></td>
                <td><?= htmlspecialchars($m['descricao']) ?></td>
                <td><?= htmlspecialchars($m['idioma']) ?></td>
                <td><?= htmlspecialchars($m['nivel']) ?></td>
                <td><?= htmlspecialchars($m['tipo']) ?></td>
                <td>
                    <?php if (!empty($m['arquivo'])): ?>
                        <a href="../../<?= $m['arquivo'] ?>" target="_blank">Ver Arquivo</a>
                    <?php else: ?>
                        Nenhum
                    <?php endif; ?>
                </td>
                <td>
                    <a href="edit_material.php?idmaterial=<?= $m['idmaterial'] ?>"> Editar</a> |
                    <a href="../../controllers/materialController.php?acao=excluir&idmaterial=<?= $m['idmaterial'] ?>" onclick="return confirm('Tem certeza que deseja excluir?')"> Excluir</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
