<html lang="pt-br">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Financeiro - Conexus</title>

<!-- font awesome cdn link  -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">

<!-- Bootstrap Icons -->
<link
rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"
/>

<!-- css file link -->
<link rel="stylesheet" href="../../../public/css/style.css">

</head>
<body>
<?php include '../components/student_header.php'; ?>
    <!-- 
    
    -->
    <section class="courses">
        <h1 class="heading">  </h1>
    
        <div class="row">
            <form method="post">
                <label for="curso" class="heading"> Escolha um curso: </label>
                <select name="curso" id="curso" class="row" style="font-size: medium;" required>
                    <option value="default" >-- Selecione um Curso --</option>
                    <option value="ingles" <?= $cursoSelecionado === 'ingles' ? 'selected' : '' ?> Inglês</option>
                    <option value="espanhol" <?= $cursoSelecionado === 'espanhol' ? 'selected' : '' ?> Espanhol</option>
                    <option value="frances" <?= $cursoSelecionado === 'frances' ? 'selected' : '' ?> Francês</option>
                </select>
                <button type="submit" class="inline-btn">Ok</button>
            </form>
        </div>
    </section>

    <?php if ($atividades): ?>
    <table>
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
            $soma += $atividade['nota'];
        ?>
            <tr>
            <td><?= htmlspecialchars($atividade['nome']) ?></td>
            <td><?= number_format($atividade['nota'], 1, ',', '.') ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
        <tfoot>
        <tr class="media">
            <td>Média</td>
            <td><?= number_format($soma / count($atividades), 2, ',', '.') ?></td>
        </tr>
        </tfoot>
    </table>
    <?php elseif ($cursoSelecionado): ?>
    <p style="text-align:center; color:red; font-size: 16px";">Curso não encontrado ou sem atividades.</p>
    <?php endif; ?>
    
<!-- custom js file link  -->
<script src="../../../public/js/script.js"></script>

</body>
</html>