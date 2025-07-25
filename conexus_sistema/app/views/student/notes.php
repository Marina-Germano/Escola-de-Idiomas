<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Boletim - Conexus</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"/>
<link rel="stylesheet" href="/escola-de-idiomas/conexus_sistema/public/css/style.css">

</head>
<body>
    <?php
    include __DIR__ . '/../components/student_header.php'; //cabeçalho
    ?>

<!-- ... cabeçalho mantido ... -->

<section class="courses">
    <h1 class="heading">Meu Boletim</h1> 
    
    <div class="row">
        <form method="post">
            <label for="curso" class="heading">Escolha um curso:</label>
            <select name="curso" id="curso" class="select-curso" required>
                <option value="default">-- Selecione um Curso --</option>
                <?php foreach ($cursosDoAluno as $curso): ?>
                    <option value="<?= htmlspecialchars($curso['id_curso']) ?>"
                        <?= $cursoSelecionado === $curso['id_curso'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($curso['nome_curso']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="inline-btn">Ver</button>
        </form>
    </div>
</section>

<?php if (!empty($cursoSelecionado) && $cursoSelecionado !== 'default' && !empty($atividades)): ?>
<table class="tabela-boletim">
    <thead>
        <tr>
            <th>Atividade</th>
            <th>Nota</th>
        </tr>
    </thead>
    <tbody>
        <?php
            $soma = 0; 
            foreach ($atividades as $atividade):
            $soma += (float)$atividade['nota'];
        ?>
        <tr>
            <td><?= htmlspecialchars($atividade['nome_atividade']) ?></td>
            <td><?= number_format((float)$atividade['nota'], 1, ',', '.') ?></td> 
        </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr class="media">
            <td>Média</td>
            <td><?= count($atividades) > 0 ? number_format($soma / count($atividades), 2, ',', '.') : 'N/A' ?></td> 
        </tr>
    </tfoot>
</table>

<?php elseif (!empty($cursoSelecionado) && $cursoSelecionado !== 'default' && empty($atividades)): ?>
    <p class="mensagem-erro">
        Nenhuma atividade encontrada para o curso "<?= htmlspecialchars($cursoSelecionado) ?>".
    </p>
<?php else: ?>
    <p class="mensagem-erro">
        Selecione um curso para ver o boletim.
    </p>
<?php endif; ?>

    
<script src="/escola-de-idiomas/conexus_sistema/public/js/script.js"></script>

</body>
</html>
