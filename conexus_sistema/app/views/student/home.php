<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Home - Conexus</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"/>
    <link rel="stylesheet" href="/escola-de-idiomas/conexus_sistema/public/css/style.css">
</head>
<body>
<?php include __DIR__ . '../../components/student_header.php'; ?>


<section class="home-grid">
    <h1 class="heading">Bem Vindo, <?= htmlspecialchars($nomeAluno ?? 'Aluno') ?>!</h1>
</section>
<!-- Próxima Aula -->
<section class="next-class">
    <h2 class="heading">Próxima Aula</h2>
    <div class="box-container">
        <?php if (isset($proximaAula)): // verificação  evento  ?>
            <div class="box">
                <p><strong>Curso:</strong> <?= htmlspecialchars($proximaAula['nome_idioma'] . ' - ' . $proximaAula['nome_turma']) ?></p>
                <p><strong>Data:</strong> <?= htmlspecialchars(date('d/m/Y', strtotime($proximaAula['data_aula']))) ?></p>
                <p><strong>Hora:</strong> <?= htmlspecialchars(substr($proximaAula['hora_inicio'], 0, 5)) ?> - <?= htmlspecialchars(substr($proximaAula['hora_fim'], 0, 5)) ?></p>
                <p><strong>Tópico:</strong> <?= htmlspecialchars($proximaAula['observacoes']) ?></p>
                <p><strong>Professor:</strong> <?= htmlspecialchars($proximaAula['professor_nome'] ?? 'N/A') ?></p>
                <p><strong>Sala:</strong> <?= htmlspecialchars($proximaAula['sala'] ?? 'Online') ?></p>
                <?php if (!empty($proximaAula['link_reuniao'])): ?>
                    <a href="<?= htmlspecialchars($proximaAula['link_reuniao']) ?>" class="inline-btn">Entrar na Aula Online</a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <p class="empty">Nenhuma aula futura agendada. </p>
        <?php endif; ?>
    </div>
</section>

<!-- Últimos Materiais -->
<section class="courses">
    <h1 class="heading">Últimos Materiais Cadastrados</h1>
    <div class="box-container">
        <?php if (!empty($ultimosMateriais)): ?>
            <?php foreach ($ultimosMateriais as $material): ?>
                <div class="box">
                    <div class="tutor">
                        <img src="<?= htmlspecialchars($material['professor_foto'] ?? '/escola-de-idiomas/conexus_sistema/public/img/pic-6.jpg') ?>" alt="Foto do Professor">
                        <div class="info">
                            <h3><?= htmlspecialchars($material['professor_nome'] ?? 'Professor Desconhecido') ?></h3>
                            <h4>Professor(a)</h4>
                            <span><?= htmlspecialchars($material['data_cadastro'] ?? '') ?></span>
                        </div>
                    </div>
                    <div class="thumb">
                        <?php
                        $materialImagemSrc = '/escola-de-idiomas/conexus_sistema/public/img/default-material.jpg';
                        if (isset($material['turma_ididioma']) && $material['turma_ididioma'] == 1) {
                            $materialImagemSrc = '/escola-de-idiomas/conexus_sistema/public/img/english-course-1024x576.jpg';
                        } elseif (!empty($material['turma_imagem'])) {
                            $materialImagemSrc = htmlspecialchars($material['turma_imagem']);
                        }
                        ?>
                        <img src="<?= $materialImagemSrc ?>" alt="Thumbnail do Material">
                        <span><?= htmlspecialchars($material['quantidade'] ?? '0') ?> arquivos</span>
                    </div>
                    <h3 class="title"><?= htmlspecialchars($material['titulo'] ?? 'Material Desconhecido') ?></h3>
                    <a href="/escola-de-idiomas/conexus_sistema/app/views/student/playlist.php?get_id=<?= htmlspecialchars($material['idmaterial'] ?? '') ?>" class="inline-btn">veja o módulo</a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="empty">Nenhum material encontrado para seus cursos.</p>
        <?php endif; ?>
    </div>
</section>

<?php if (isset($erroHome)): ?>
    <div class="message form">
        <span><?= htmlspecialchars($erroHome) ?></span>
    </div>
<?php endif; ?>


<script src="/escola-de-idiomas/conexus_sistema/public/js/script.js"></script>

</body>
</html>
