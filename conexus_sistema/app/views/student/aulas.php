<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login - Conexus</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">
  
  <!-- Bootstrap Icons -->
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"
  />

  <!-- css file link -->
  <link rel="stylesheet" href="../public/css/style.css">

</head>
<body>
    <!-- header section strats -->
    <header class="header">
    <section class="flex">
        <a href="/app/views/student/home.html" class="logo"> <img src="/public/img/conexus_sem_fundo.png" alt=""> Conexus </a>
        <form action="" method="post" class="search-form">
        <input type="text" name="search_box" placeholder="Pesquisar..."  required maxlength="100" id="search_box">
        <button type="submit" class="bi bi-search" name="search_box"></button>
        </form>
    <div class="icons">
        <div id="menu-btn" class="bi bi-list"></div>
        <div id="search-btn" class="bi bi-search"></div>
        <div id="user-btn" class="bi bi-person"></div>
        <div id="toggle-btn" class="bi bi-brightness-high"></div>
    </div>

    <div class="profile">
        <img src="/public/img/pic-1-removebg-preview.png" alt="">
        <h3>Alexandre Quintão</h3>
        <span>2024002167</span>
        <a href="profile.html" class="btn">acessar perfil</a>
        <div class="flex-btn">
            <a href="login.html" class="option-btn">login</a>
            <a href="register.html" class="option-btn">sair</a>
        </div>
    </div>
    </section>  
    </header>
    <!-- header section ends -->
    <!-- sidebar section starts -->
    <div class="side-bar">

      <div id="close-btn">
         <i class="fas fa-times"></i>
      </div>

      <div class="profile">
         <img src="/public/img/pic-1-removebg-preview.png" alt="">
         <h3>Alexandre Quintão</h3>
         <span>2024002167</span>
         <a href="profile.html" class="btn">acessar perfil</a>
      </div>

      <nav class="navbar">
         <a href="home.html"><i class="fas fa-home"></i><span>home</span></a>
         <a href="aulas.html"><i class="bi bi-calendar-week"></i><span>aulas</span></a>
         <a href="materiais.html"><i class="bi bi-folder-check"></i><span>materiais</span></a>
         <a href="boletim.html"><i class="bi bi-reception-4"></i><span>boletim</span></a>
         <a href="financeiro.html"><i class="bi bi-piggy-bank-fill"></i><span>financeiro</span></a>
         <a href="contact.html"><i class="fas fa-headset"></i><span>nos contate</span></a>
      </nav>
    </div>
    <!-- sidebar section ends -->
    <!-- classes section starts -->
          <section class="container">
    <h2 class="heading">Próximas Aulas</h2>

    <div class="box">
      <div id="header">
        <div class="flex-btn">
          <button class="inline-btn" id="backButton">Voltar</button>
          <button class="inline-btn" id="nextButton">Próximo</button>
        </div>
        <div id="monthDisplay" class="title"></div>
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

      <div id="calendar" style="margin-top: 2rem;"></div>
    </div>

    <div class="box" id="newEventModal" style="display:none;">
      <h3 class="title">Novo Evento</h3>
      <input id="eventTitleInput" class="box" placeholder="Título do Evento"/>
      <div class="flex-btn">
        <button class="inline-btn" id="saveButton">Salvar</button>
        <button class="inline-delete-btn" id="cancelButton">Cancelar</button>
      </div>
    </div>

    <div class="box" id="deleteEventModal" style="display:none;">
      <h3 class="title">Detalhes do Evento</h3>
      <div id="eventText"></div>
      <div class="flex-btn">
        <button class="inline-delete-btn" id="deleteButton">Deletar</button>
        <button class="inline-option-btn" id="closeButton">Fechar</button>
      </div>
    </div>

    <div id="modalBackDrop" style="display:none;"></div>
  </section>

    <!-- classes section ends -->
<!-- custom js file link  -->
<script src="/public/js/script.js"></script>
<?php
  // Preenche os dias "padding" antes do primeiro dia do mês
  for ($i = 0; $i < $firstWeekday; $i++) {
      echo "<div class='day padding'></div>";
  }

  // Preenche os dias reais do mês
  for ($day = 1; $day <= $daysInMonth; $day++) {
      $dateString = sprintf('%04d-%02d-%02d', $year, $month, $day);
      $isToday = (new DateTime())->format('Y-m-d') === $dateString;
      
      $eventFound = null;
      foreach ($events as $event) {
          if ($event['date'] === $dateString) {
              $eventFound = $event['title'];
              break;
          }
      }
      $idCurrent = $isToday ? "id='currentDay'" : "";
      echo "<div class='day' $idCurrent>$day";
      if ($eventFound) {
          echo "<div class='event'>$eventFound</div>";
      }
      echo "</div>";
  }
?>
</body>