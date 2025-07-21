<?php
session_start();
require_once(__DIR__ . '/../../config/conexao.php');

if (!isset($_SESSION['idusuario']) || $_SESSION['papel'] !== 'admin') {
   header('Location: /conexus_sistema/app/views/login.php');
   exit;
}

$idusuario = $_SESSION['idusuario'];
$conn = Conexao::conectar();

// Busca nome do admin
$stmt = $conn->prepare("SELECT nome FROM usuario WHERE idusuario = ?");
$stmt->execute([$idusuario]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);
$nome = $usuario['nome'] ?? 'Administrador';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Dashboard Admin</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
   <link rel="stylesheet" href="../../../public/css/admin_style.css">
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="dashboard">
   <h1 class="heading">Painel do Administrador</h1>

   <div class="box-container">

      <div class="box">
         <h3>Bem-vindo, <?= htmlspecialchars($nome) ?>!</h3>
         <p>Administrador do sistema</p>
      </div>

      <div class="box">
         <h3>Relatório Financeiro</h3>
         <p>Acompanhe ganhos</p>
         <a href="../../controllers/relatorioFinanceiroController.php" target="_blank" class="btn">Ver Relatório</a>
      </div>

      <div class="box">
         <h3>Relatório de Materiais</h3>
         <p>Acompanhe o uso de materiais</p>
         <a href="../../controllers/relatorioMateriaisController.php" target="_blank" class="btn">Ver Relatório</a>
      </div>

      <div class="box">
         <h3>Cadastrar Aluno</h3>
         <p>Gerenciar alunos</p>
         <a href="register_student.php" class="btn">Cadastrar Aluno</a>
      </div>

      <div class="box">
         <h3>Cadastrar Turma</h3>
         <p>Gerencar turmas</p>
         <a href="register_class.php" class="btn">Cadastrar Turma</a>
      </div>

      <div class="box">
         <h3>Cadastrar Funcionário</h3>
         <p>Gerenciar equipe administrativa</p>
         <a href="register_employer.php" class="btn">Cadastrar Funcionário</a>
      </div>

   </div>
</section>


<script src="../../../public/js/admin_script.js"></script>
</body>
</html>
