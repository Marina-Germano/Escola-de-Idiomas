<?php
session_start();
require_once(__DIR__ . '/../../config/conexao.php');

if (!isset($_SESSION['idusuario']) || $_SESSION['papel'] !== 'aluno') {
   header('Location: /conexus_sistema/app/views/login.php');
   exit;
}

$idusuario = $_SESSION['idusuario'];
$conn = Conexao::conectar();

?>
<html lang="pt-br">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Boletim - Conexus</title>

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

    <section class="courses">
        <h1 class="heading">Notas por Curso</h1>
    
        <div class="row">
            <form method="post">
                <label for="curso" class="heading"> Escolha um curso: </label>
                <select name="curso" id="curso" class="row" style="font-size: medium;" required>
                    <option value="default" >-- Selecione um Curso --</option>
                    <option value="ingles" <?= $cursoSelecionado === 'ingles' ? 'selected' : '' ?> Inglês</option>
                    <option value="espanhol" <?= $cursoSelecionado === 'espanhol' ? 'selected' : '' ?> Espanhol</option>
                    <option value="frances" <?= $cursoSelecionado === 'frances' ? 'selected' : '' ?> Francês</option>
                </select>
                <button type="submit" class="inline-btn">Ver Notas</button>
            </form>
        </div>
    </section>

<?php

$atividades = []; 

$cursoSelecionado = isset($_GET['curso']) ? htmlspecialchars($_GET['curso']) : 'Nenhum Curso Selecionado'; 

?>
<script src="/public/js/script.js"></script>

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
                <td><?= count($atividades) > 0 ? number_format($soma / count($atividades), 2, ',', '.') : 'N/A' ?></td>
            </tr>
        </tfoot>
    </table>
<?php elseif ($cursoSelecionado): ?>
    <p style="text-align:center; color:red; font-size: 16px;">
        O curso "<?= htmlspecialchars($cursoSelecionado) ?>" não foi encontrado ou não tem atividades.
    </p>
<?php else: ?>
    <p style="text-align:center; color:red; font-size: 16px;">
        Nenhum curso selecionado ou atividades disponíveis.
    </p>
<?php endif; ?>
    
<!-- custom js file link  -->
<script src="../../../public/js/script.js"></script>

</body>
</html>