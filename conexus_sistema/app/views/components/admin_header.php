<?php
session_start();
require_once(__DIR__ . '/../../config/conexao.php');

// Verifica se o admin está logado
if (!isset($_SESSION['idusuario']) || $_SESSION['papel'] !== 'admin') {
   header('Location: /conexus_sistema/app/views/login.php');
   exit;
}

$idusuario = $_SESSION['idusuario'];

$stmt = $conn->prepare("SELECT nome FROM usuario WHERE idusuario = ?");
$stmt->execute([$idusuario]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);
$nome_admin = $usuario['nome'] ?? 'Admin';
?>

<header class="admin-header">
   <h2>Bem-vindo, <?= htmlspecialchars($nome_admin) ?></h2>
   <nav>
      <a href="/conexus_sistema/app/views/admin/dashboard.php">Dashboard</a>
      <a href="/conexus_sistema/app/views/admin/profile.php">Perfil</a>
      <a href="/conexus_sistema/app/views/logout.php">Sair</a>
   </nav>
</header>
