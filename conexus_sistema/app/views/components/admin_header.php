<!-- Mensagens -->
<?php
// Iniciar sessão se ainda não estiver iniciada
if (session_status() === PHP_SESSION_NONE) {
   session_start();
}

// Incluir a conexão com o banco
require_once(__DIR__ . '/../../config/conexao.php');


// Verificar se há um usuário logado
$idusuario = $_SESSION['idusuario'] ?? null;
$fetch_profile = null;

if ($idusuario !== null) {
   try {
      $conn = Conexao::conectar();
      $select_profile = $conn->prepare("SELECT * FROM usuario WHERE idusuario = ?");
      $select_profile->execute([$idusuario]);
      if ($select_profile->rowCount() > 0) {
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
      }
   } catch (PDOException $e) {
        // Em produção, use log ao invés de exibir erros
      echo "Erro ao buscar perfil: " . $e->getMessage();
   }
}
?>

<header class="header">
   <section class="flex">
      <a href="dashboard.php" class="logo">
         <img src="../../../public/img/conexus_sem_fundo.png" alt=""> Conexus
      </a>

      <form action="search_page.php" method="post" class="search-form">
         <input type="text" name="search" placeholder="search here..." required maxlength="100">
         <button type="submit" class="fas fa-search" name="search_btn"></button>
      </form>

      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <div id="search-btn" class="fas fa-search"></div>
         <div id="user-btn" class="fas fa-user"></div>
         <div id="toggle-btn" class="fas fa-sun"></div>
      </div>

      <div class="profile">
         <?php if ($fetch_profile): ?>
            <img src="../../../public/img/pic-1.jpg" alt="Foto do usuário">
            <h3><?= htmlspecialchars($fetch_profile['nome']) ?></h3>
            <span><?= htmlspecialchars($fetch_profile['papel']) ?></span>
         <?php else: ?>
            <p>Usuário não identificado</p>
         <?php endif; ?>
      </div>
   </section>
</header>


<!-- header section ends -->

<!-- side bar section starts  -->

<div class="side-bar">

   <div class="close-side-bar">
      <i class="fas fa-times"></i>
   </div>


      <nav class="navbar">
         <a href="dashboard.php"><i class="fas fa-home"></i><span>Home</span></a>
         <a href="list_class.php"><i class="fa-solid fa-bars-staggered"></i><span>Listar Turmas</span></a>
         <a href="list_students.php"><i class="fas fa-graduation-cap"></i><span>Listar Estudantes</span></a>
         <a href="list_employer.php"><i class="fas fa-comment"></i><span>Listar Funcionários</span></a>
         <a href="list_material.php"><i class="fas fa-comment"></i><span>Listar Materiais</span></a>
         <a href="material_loan.php"><i class="fas fa-comment"></i><span>Gerenciar Emprestimos</span></a>
         <a href="../components/admin_logout.php" onclick="return confirm('logout from this website?');"><i class="fas fa-right-from-bracket"></i><span>Sair</span></a>
      </nav>
</div>

<!-- side bar section ends -->



