<?php
session_start();
require_once "../models/calendario_aula.php";

$calendario = new CalendarioAula();

function estaLogado() {
    return isset($_SESSION['idusuario']);
}

if (!isset($_SESSION['idusuario']) || ($_SESSION['papel'] !== 'aluno' && $_SESSION['papel'] !== 'admin')) {
    header('Location: ../views/login.php');
    exit();
}

if (!estaLogado()) {
    http_response_code(401);
    echo "Acesso negado. Faça login para continuar.";
    exit;
}

if (!isset($_GET['acao'])) {
    echo "Nenhuma ação definida.";
    exit;
}

$acao = $_GET['acao'];
// pega o ID do usuário e o papel da sessão.
$idusuario = $_SESSION['idusuario'];
$papelUsuario = $_SESSION['papel'];
error_log("DEBUG: idusuario logado: " . $idusuario . ", Papel: " . $papelUsuario);

$year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');
$month = isset($_GET['month']) ? (int)$_GET['month'] : date('m');

if ($month < 1 || $month > 12) {
    $month = date('m');
}

// calcula o dia da semana do primeiro dia do mês e o número total de dias no mês.
$firstWeekday = date('w', strtotime("$year-$month-01"));
$daysInMonth = date('t', strtotime("$year-$month-01"));

// Array com os nomes dos meses
$monthNames = [
    1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março', 4 => 'Abril',
    5 => 'Maio', 6 => 'Junho', 7 => 'Julho', 8 => 'Agosto',
    9 => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro'
];
$currentMonthName = $monthNames[(int)$month];
$calendarioAulaModel = new CalendarioAula();

$events = [];

//buscar evetos
try {
    // chama 'getEventsAlunos' do modelo para buscar TODOS os eventos no BD
    $events = $calendarioAulaModel->getEventsAlunos($idusuario);
    error_log("DEBUG: getEventsAlunos executado com sucesso. " . count($events) . " eventos encontrados.");
} catch (PDOException $e) {
    // captura exceções específicas do PDO (erros de banco de dados).
    error_log("ERRO PDO em calendariosControllers ao buscar eventos: " . $e->getMessage());
    // a requisição for AJAX, retorna um JSON com o erro e status 500.
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest' || isset($_GET['ajax'])) {
        http_response_code(500); // define o status HTTP como 500 (Erro Interno do Servidor)
        header('Content-Type: application/json'); // define o tipo de conteúdo como JSON
        echo json_encode(['error' => 'Erro interno do servidor ao carregar eventos do calendário.', 'details' => $e->getMessage()]);
        exit; // para
    } else {
        //  não for AJAX, exibe uma mensagem de erro
        die("Erro ao carregar dados do calendário. Por favor, tente novamente mais tarde.");
    }
} catch (Exception $e) {
    // verifica exceções q pode acontecer
    error_log("ERRO GERAL em calendariosControllers ao buscar eventos: " . $e->getMessage());
    // se requisição for AJAX, retorna um JSON com o erro e status 500.
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest' || isset($_GET['ajax'])) {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Ocorreu um erro inesperado do servidor ao carregar eventos.', 'details' => $e->getMessage()]);
        exit;
    } else {
        // se não for AJAX, exibe uma mensagem de erro
        die("Ocorreu um erro inesperado. Por favor, tente novamente mais tarde.");
    }
}

//  responde com JSON contendo os eventos.
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest' || isset($_GET['ajax'])) {
    header('Content-Type: application/json');
    echo json_encode(['events' => $events]); // Envia tds os eventos para o frontend via AJAX
    error_log("DEBUG: Resposta AJAX enviada com " . count($events) . " eventos.");
    exit; // para
}

require_once '../views/student/aulas.php'; // inclui a view para exibir o HTML completo do calendário.
?>

