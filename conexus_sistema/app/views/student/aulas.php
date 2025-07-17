<?php
session_start();
require_once(__DIR__ . '/../../config/conexao.php');

if (!isset($_SESSION['idusuario']) || $_SESSION['papel'] !== 'aluno') {
  header('Location: /conexus_sistema/app/views/login.php');
  exit;
}

$idusuario = $_SESSION['idusuario'];
$conn = Conexao::conectar();

// --- INÍCIO: DEFINIÇÃO DAS VARIÁVEIS PARA O CALENDÁRIO ---
// (Estas variáveis já foram definidas e corrigidas anteriormente.
// Mantemos a estrutura que você já tem para elas, no topo do seu PHP)

$year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');
$month = isset($_GET['month']) ? (int)$_GET['m'] : date('m'); // Certifique-se de que é 'm' para o mês numérico

if ($month < 1 || $month > 12) {
    $month = date('m');
}

$firstWeekday = date('w', strtotime("$year-$month-01"));
$daysInMonth = date('t', strtotime("$year-$month-01")); 

$events = []; // Continua como um array vazio até que você o preencha com dados reais.

// --- FIM: DEFINIÇÃO DAS VARIÁVEIS ---

?>

<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Aulas - Conexus</title> <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">
  
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"
  />

  <link rel="stylesheet" href="../../../public/css/style.css">

</head>
<body>
    <?php include '../components/student_header.php'; ?>
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

      <div id="calendar" style="margin-top: 2rem;">
        <?php
        // --- ESTE É O BLOCO DE CÓDIGO PHP QUE FOI MOVIDO PARA AQUI DENTRO! ---
        // Ele estava antes, solto, depois da div #calendar.
        // Agora, ele está DENTRO dela, garantindo que os dias sejam gerados no lugar certo.

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
        // --- FIM DO BLOCO DE CÓDIGO PHP MOVIDO ---
        ?>
      </div>
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

  <script src="../../../public/js/script.js"></script>
</body>
</html>