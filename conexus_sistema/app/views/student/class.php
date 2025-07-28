<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Aulas - Conexus</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">
    <link rel="stylesheet"href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"/>
    <link rel="stylesheet" href="/escola-de-idiomas/conexus_sistema/public/css/style.css">
</head>
<body>
    <?php
    include __DIR__ . '/../components/student_header.php'; //cabeçalho
    ?>
    <section class="container">
    <h2 class="heading">Próximas Aulas</h2> 

    <div class="box">
        <div id="header">
            <div class="flex-btn">
                <button class="inline-btn" id="backButton">Voltar</button> 
                <button class="inline-btn" id="nextButton">Próximo</button>
            </div>
            <div id="monthDisplay" class="title"><?= $currentMonthName; ?>, <?= $year; ?></div>
        </div>

        <div id="weekdays" class="flex" style="margin-top: 2rem; gap: 1rem;">
            <div>Domingo</div>
            <div>Segunda-feira</div>
            <div>Terça-feira</div>
            <div>Quarta-feira</div>
            <div>Quinta-feira</div>
            <div>Sexta-feira</div>
            <div>Sábado</div>
        </div>

        <div id="calendar" style="margin-top: 2rem;">
        </div>
    </div>
    </section>

<script> // passado os dados para o JS months são 0-11
    const phpCurrentYear = <?= $year ?>; 
    const phpCurrentMonth = <?= $month - 1 ?>; 
</script>

<script src="/escola-de-idiomas/conexus_sistema/public/js/script.js"></script>
</body>
</html>