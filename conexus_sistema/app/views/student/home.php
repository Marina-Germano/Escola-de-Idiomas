<?php
session_start();
require_once(__DIR__ . '/../../config/conexao.php');

if (!isset($_SESSION['idusuario']) || $_SESSION['papel'] !== 'aluno') {
    header('Location: ../login.php');
    exit;
}

$idusuario = $_SESSION['idusuario'];
$conn = Conexao::conectar();

// Buscar dados do aluno com base no idusuario
$stmt = $conn->prepare("SELECT a.idaluno, u.nome FROM aluno a JOIN usuario u ON a.idusuario = u.idusuario WHERE a.idusuario = ?");
$stmt->execute([$idusuario]);
$fetch_user = $stmt->fetch(PDO::FETCH_ASSOC);
?>



<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Home - Conexus</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">
    
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"
    />

    <link rel="stylesheet" href="../../../public/css/style.css">

</head>
<body>
<?php include '../components/student_header.php'; ?>
    
<section class="home-grid">
    <h1 class="heading">Bem Vindo!</h1>
    <div class="box-container">
        <div class="box">
            <h3 class="title">Atualizações</h3>
            <p class="likes">materiais novos: <span>25</span></p>
            <a href="#" class="inline-btn">veja aqui</a>
            <p class="likes">notas novas: </p>
            <a href="#" class="inline-btn">veja aqui</a>
            <p class="likes">próximas aulas: </p>
            <a href="aulas.php" class="inline-btn">veja aqui</a>
        </div>
        <div class="box">
            <h3 class="title">Próximas Aulas</h3>
            <div class="flex">
                <a href="aulas.php"><i class="bi bi-calendar-check-fill"></i><span>Espanhol - Sala 15</span></a>
                <a href="aulas.php"><i class="bi bi-calendar-check-fill"></i><span>Inglês - Sala Online</span></a>
                <a href="aulas.php"><i class="bi bi-calendar-check-fill"></i><span>Espanhol - Sala 15</span></a>
                <a href="aulas.php"><i class="bi bi-calendar-check-fill"></i><span>Inglês - Sala Online</span></a>
            </div>
        </div>
        <div class="box">
            <h3 class="title">Ultimas Notas</h3>
            <div class="flex">
                <a href="boletim.html"><i class="bi bi-award-fill"></i><span>Atividade Avaliativa 01</span></a>
                <a href="boletim.html"><i class="bi bi-award-fill"></i><span>Atividade Avaliativa 02</span></a>
                <a href="boletim.html"><i class="bi bi-award-fill"></i><span>Atividade Avaliativa 03</span></a>
                <a href="boletim.html"><i class="bi bi-award-fill"></i><span>Atividade Avaliativa 04</span></a>
            </div>
        </div>
    </div>
</section>

<section class="courses">
    <h1 class="heading">Seus Materiais</h1>
    <div class="box-container">
        <?php
        if (!empty($all_materials)) {
            foreach ($all_materials as $material) {
                $tutor_name = $material['professor'] ?? 'Professor Desconhecido';
                $tutor_image = $material['foto_professor'] ?? 'pic-7.jpg';
                
                // coluna do material
                $material_title = $material['titulo'];
                $material_files_count = $material['quantidade'] ?? 'N/A';
                $material_id = $material['idmaterial'];
                $material_date = date('d/m/Y');
                if (isset($material['arquivo']) && !empty($material['arquivo'])) {
                    $file_ext = pathinfo($material['arquivo'], PATHINFO_EXTENSION);
                    if (in_array($file_ext, ['jpg', 'jpeg', 'png', 'gif'])) {
                        $material_thumb = $material['arquivo'];
                    }
                }
        ?>
        <div class="box">
            <div class="tutor">
                <img src="/conexus_sistema/public/img/<?= $tutor_image; ?>" alt="">
                <div class="info">
                    <h3><?= $tutor_name; ?></h3>
                    <h4>Professor(a)</h4>
                    <span><?= $material_date; ?></span>
                </div>
            </div>
            <div class="thumb">
                <img src="/conexus_sistema/public/img/<?= $material_thumb; ?>" alt="">
                <span><?= $material_files_count; ?> arquivos</span>
            </div>
            <h3 class="title"><?= $material_title; ?></h3>
            <a href="/conexus_sistema/app/views/student/playlist.php?get_id=<?= $material_id; ?>" class="inline-btn">veja o módulo</a>
        </div>
        <?php
            }
        } else {
            echo '<p class="empty">Nenhum material adicionado ainda!</p>';
        }
        ?>
    </div>
    <div class="more-btn">
        <a href="materiais.php" class="inline-option-btn">Veja todos Materiais</a>
    </div>
</section>

<script src="../../../public/js/script.js"></script>

</body>
</html>
