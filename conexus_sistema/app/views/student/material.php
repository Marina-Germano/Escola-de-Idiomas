<?php
session_start();
require_once __DIR__ . '/../../models/material.php';

$idaluno = $_SESSION['idusuario'] ?? null;

$materialModel = new Material();

if ($idaluno) {
    $materiaisDoAluno = $materialModel->listarMateriaisPorAluno($idaluno);
} else {
    $materiaisDoAluno = [];
}

// a partir daqui vem o HTML da sua view
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Materiais - Conexus</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"/>
    <link rel="stylesheet" href="/escola-de-idiomas/conexus_sistema/public/css/style.css">
</head>
<body>
    <?php
    include __DIR__ . '/../components/student_header.php'; //cabeçalho

    $materiaisDoAluno = $materiaisDoAluno ?? [];
    ?>
    <section class="courses">
        <h1 class="heading">Seus Materiais</h1>
        <div class="box-container">
            <?php if (!empty($materiaisDoAluno)): ?>
                <?php foreach ($materiaisDoAluno as $material): ?>
                    <div class="box">
                        <div class="tutor">
                            <img src="<?= htmlspecialchars($material['professor_foto'] ?? '/escola-de-idiomas/conexus_sistema/public/img/pic-1.jpg') ?>" alt="Foto do Professor">
                            <div class="info">
                                <h3><?= htmlspecialchars($material['professor_nome'] ?? 'Professor Desconhecido') ?></h3>
                                <h4>Professor(a)</h4>
                                <span><?= htmlspecialchars(date('d/m/Y', strtotime($material['data_cadastro'] ?? ''))) ?></span>
                            </div>
                        </div>
                        <div class="thumb">
                            <img src="<?= htmlspecialchars($material['turma_imagem'] ?? '/escola-de-idiomas/conexus_sistema/public/img/english-course-1024x576.jpg') ?>" alt="Thumbnail do Material">
                            <span><?= htmlspecialchars($material['quantidade'] ?? '0') ?> arquivos</span>
                        </div>
                        <h3 class="title"><?= htmlspecialchars($material['titulo'] ?? 'Material Desconhecido') ?></h3>
                        <a href="" class="inline-btn">Acessar módulo</a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="empty" style="text-align: center; padding: 2rem; color: #666;">Nenhum material encontrado para seus cursos.</p>
            <?php endif; ?>
        </div>
    </section>

    <script src="/escola-de-idiomas/conexus_sistema/public/js/script.js"></script>
</body>
</html>
