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
      $select_profile = $conn->prepare("SELECT * FROM `funcionario` WHERE id = ?");
      $select_profile->execute([$cpf]);
      if($select_profile->rowCount() > 0){
         $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
      <img src="../../arquivo/<?= $fetch_profile['foto']; ?>" alt="">
      <h3><?= $fetch_profile['nome']; ?></h3>
      <span><?= $fetch_profile['cargo']; ?></span>
      <?php
      }
      ?>
      <?php echo "ok"; ?>
</div>

   </section>

</header>

<!-- header section ends -->

<!-- side bar section starts  -->

<div class="side-bar">

   <div class="close-side-bar">
      <i class="fas fa-times"></i>
   </div>

  <div class="profile">
   <?php
      $select_profile = $conn->prepare("SELECT * FROM `funcionario` WHERE id = ?");
      $select_profile->execute([$cpf]);
      if($select_profile->rowCount() > 0){
         $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
   ?>
      <img src="../../arquivo/<?= $fetch_profile['foto']; ?>" alt="">
      <h3><?= $fetch_profile['nome']; ?></h3>
      <span><?= $fetch_profile['cargo']; ?></span>
   <?php
      }
   ?>
</div>

      <nav class="navbar">
         <a href="dashboard.html"><i class="fas fa-home"></i><span>Home</span></a>
         <a href="aula_cadastro.html"><i class="fa-solid fa-bars-staggered"></i><span>Cadastro de Aula</span></a>
         <a href="register_student.html"><i class="fas fa-graduation-cap"></i><span>Cadastro de Aluno</span></a>
         <a href="register_employer.html"><i class="fas fa-comment"></i><span>Cadastro de Funcionario</span></a>
         <a href="update_content.html"><i class="fas fa-comment"></i><span>Cadastro de Materiais</span></a>
         <a href="../components/admin_logout.php" onclick="return confirm('logout from this website?');"><i class="fas fa-right-from-bracket"></i><span>logout</span></a>
      </nav>
</div>

<!-- side bar section ends -->