<!-- Mensagens -->
<?php
if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>

<header class="header">
   <section class="flex">
      <a href="dashboard.php" class="logo">Admin.</a>
      
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
         <?php
         $idusuario = $_SESSION['idusuario'] ?? null;
         if ($idusuario !== null) {
            $select_profile = $conn->prepare("SELECT * FROM `usuario` WHERE idusuario = ?");
            $select_profile->execute([$idusuario]);
            if($select_profile->rowCount() > 0){
               $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
               <img src="../../../public/img/pic-1.jpg<?= $fetch_profile['foto']; ?>" alt="">
               <h3><?= $fetch_profile['nome']; ?></h3>
               <span><?= $fetch_profile['papel']; ?></span>
         <?php
            }
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
         <a href="dashboard.php"><i class="fas fa-home"></i><span>Home</span></a>
         <a href="turma_cadastro.php"><i class="fa-solid fa-bars-staggered"></i><span>Cadastro de Turma</span></a>
         <a href="register_student.php"><i class="fas fa-graduation-cap"></i><span>Cadastro de Aluno</span></a>
         <a href="register_employer.php"><i class="fas fa-comment"></i><span>Cadastro de Funcionario</span></a>
         <a href="register_material.php"><i class="fas fa-comment"></i><span>Cadastro de Materiais</span></a>
         <a href="../components/admin_logout.php" onclick="return confirm('logout from this website?');"><i class="fas fa-right-from-bracket"></i><span>logout</span></a>
      </nav>
</div>

<!-- side bar section ends -->



