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

    <a href="../student/home.php" class="logo"> <img src="../../../public/img/conexus_sem_fundo.png" alt=""> Conexus </a>

      <form action="search_course.php" method="post" class="search-form">
         <input type="text" name="search_course" placeholder="search courses..." required maxlength="100">
         <button type="submit" class="fas fa-search" name="search_course_btn"></button>
      </form>

      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <div id="search-btn" class="fas fa-search"></div>
         <div id="user-btn" class="fas fa-user"></div>
         <div id="toggle-btn" class="fas fa-sun"></div>
      </div>

      <div class="profile">
         <?php
         $idusuario = $_SESSION['idusuario'] ?? null;
         if ($idusuario !== null) {
            $select_profile = $conn->prepare("SELECT * FROM `usuario` WHERE idusuario = ?");
            $select_profile->execute([$idusuario]);
         ?>
               <img src="../../../public/img/pic-1.jpg<?= $fetch_profile['foto']; ?>" alt="">
               <h3><?= $fetch_profile['nome']; ?></h3>
               <span><?= $fetch_profile['papel']; ?></span>
                <div class="flex-btn">
                    <a href="../components/admin_logout.php" onclick="return confirm('logout from this website?');"><i class="fas fa-right-from-bracket"></i><span>Sair</span></a>
                </div>
         <?php
            }
         ?>
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
        <a href="/escola-de-idiomas/conexus_sistema/app/controllers/homeController.php"><i class="fas fa-home"></i><span>Home</span></a>
        <a href="/escola-de-idiomas/conexus_sistema/app/controllers/calendarioController.php"><i class="bi bi-calendar-week"></i><span>Aulas</span></a>
        <a href="/escola-de-idiomas/conexus_sistema/app/controllers/materialController.php?acao=listar_aluno"><i class="bi bi-folder-check"></i><span>Materiais</span></a> 
        <a href="/escola-de-idiomas/conexus_sistema/app/controllers/avaliacaoController.php"><i class="bi bi-reception-4"></i><span>Boletim</span></a>
        <a href="/escola-de-idiomas/conexus_sistema/app/controllers/pagamentoController.php"><i class="bi bi-piggy-bank-fill"></i><span>Financeiro</span></a>
        <a href="/escola-de-idiomas/conexus_sistema/app/controllers/contatoController.php"><i class="fas fa-headset"></i><span>Nos contate</span></a>
    </nav>
</div>

<!-- side bar section ends -->