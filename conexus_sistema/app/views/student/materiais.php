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


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Materiais - Conexus</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />

    <link rel="stylesheet" href="../../../public/css/style.css">
</head>
<body>
<?php include '../components/student_header.php'; ?>
    </div>
    <section class="courses">
        <h1 class="heading">Seus Materiais</h1>
        <div class="box-container">
            <?php
            // ver se tem erro e nao tem  material pra mostrar
            if (isset($erroMateriais)) {
                echo '<p class="error-message">' . htmlspecialchars($erroMateriais) . '</p>';
            } elseif (empty($materiaisDoAluno)) {
                echo '<p class="info-message">Você ainda não tem materiais disponíveis!.</p>';
            } else {
                // loop percorrer todo material
                foreach ($materiaisDoAluno as $material):
            ?>
                    <div class="box">
                        <div class="tutor">
                            <img src="/conexus_sistema/public/img/<?= htmlspecialchars($material['foto_professor'] ?? 'default-pic.jpg'); ?>" alt="Foto do Professor">
                            <div class="info">
                                <h3><?= htmlspecialchars($material['professor'] ?? 'Professor Desconhecido'); ?></h3>
                                <h4>Professor(a)</h4>
                                <span><?= htmlspecialchars($material['data_cadastro'] ?? 'Data Indisponível'); ?></span>
                            </div>
                        </div>
                        <div class="thumb">
                            <?php
                            // Imagem padrão caso o arquivo não seja uma imagem reconhecida ou não exista.
                            $material_image_path = '/conexus_sistema/public/img/default-course.jpg';

                            if (isset($material['arquivo']) && !empty($material['arquivo'])) {
                                $file_extension = pathinfo($material['arquivo'], PATHINFO_EXTENSION);
                                $file_extension_lower = strtolower($file_extension); // Converte para minúsculas para comparação segura.

                                $allowed_image_extensions = ['jpg', 'jpeg', 'png', 'webp']; // Tipos de imagem que reconhecemos.

                                if (in_array($file_extension_lower, $allowed_image_extensions)) {
                                    // Se o arquivo for uma imagem, usamos o caminho completo que veio do banco.
                                    $material_image_path = '/conexus_sistema/' . htmlspecialchars($material['arquivo']);
                                } else {
                                    // Se não for uma imagem, usamos um 'switch' para escolher o ícone certo.
                                    switch ($file_extension_lower) {
                                        case 'pdf':
                                            $material_image_path = '/conexus_sistema/public/img/icon-pdf.png';
                                            break;
                                        case 'doc':
                                        case 'docx':
                                            $material_image_path = '/conexus_sistema/public/img/icon-doc.png';
                                            break;
                                        case 'xls':
                                        case 'xlsx':
                                            $material_image_path = '/conexus_sistema/public/img/icon-xls.png';
                                            break;
                                        case 'ppt':
                                        case 'pptx':
                                            $material_image_path = '/conexus_sistema/public/img/icon-ppt.png';
                                            break;
                                        case 'zip':
                                        case 'rar':
                                            $material_image_path = '/conexus_sistema/public/img/icon-zip.png';
                                            break;
                                        case 'mp3':
                                        case 'wav':
                                            $material_image_path = '/conexus_sistema/public/img/icon-audio.png';
                                            break;
                                        case 'mp4':
                                        case 'avi':
                                        case 'mov':
                                            $material_image_path = '/conexus_sistema/public/img/icon-video.png';
                                            break;
                                        default:
                                            // se não for nenhuma das extensões 
                                            $material_image_path = '/conexus_sistema/public/img/icon-document.png';
                                            break;
                                    }
                                }
                            }
                            ?>
                            <img src="<?= $material_image_path; ?>" alt="Thumbnail do Material">
                            <span><?= htmlspecialchars($material['quantidade'] ?? 'N/A'); ?> arquivos</span>
                        </div>
                        <h3 class="title"><?= htmlspecialchars($material['titulo'] ?? 'Título do Módulo'); ?></h3>
                        <a href="/conexus_sistema/app/views/student/playlist.php?get_id=<?= htmlspecialchars($material['idmaterial']); ?>" class="inline-btn">veja o módulo</a>
                    </div>
            <?php
                endforeach;
            }
            ?>
        </div>
    </section>
    <script src="../../../public/js/script.js"></script>
</body>
</html>