<?php
session_start();
require_once(__DIR__ . '/../../config/conexao.php');

if (!isset($_SESSION['idusuario']) || $_SESSION['papel'] !== 'professor') {
   //header('Location: /conexus_sistema/app/views/login.php');
   echo $_SESSION['papel'];
   exit;
}

$idusuario = $_SESSION['idusuario'];
$conn = Conexao::conectar();

// Busca nome do admin
$stmt = $conn->prepare("SELECT nome FROM usuario WHERE idusuario = ?");
$stmt->execute([$idusuario]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);
$nome = $usuario['nome'] ?? 'Professor';

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Dashboard Funcionário</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
   <link rel="stylesheet" href="../../../public/css/teacher_style.css">
</head>
<body>

<?php include '../components/teacher_header.php'; ?>

<section class="dashboard">
   <h1 class="heading">Painel do Professor</h1>

   <div class="box-container">

      <div class="box">
         <h3>Bem-vindo, <?= htmlspecialchars($nome) ?>!</h3>
         <p>Usuário do sistema</p>
      </div>

      <div class="box">
         <h3>Cadastrar Material</h3>
         <p>Gerencie os materiais da escola</p>
         <a href="register_material.php" class="btn">Cadastrar Materiais</a>
      </div>


      <div class="box">
         <h3>Relatório de Materiais</h3>
         <p>Acompanhe o uso de materiais</p>
         <a href="../../controllers/relatorioMateriaisController.php" class="btn">Ver Relatório</a>
      </div>

   </div>
</section>


<script src="../../../public/js/admin_script.js"></script>
</body>
</html>

