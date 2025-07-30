<?php

session_start();

require_once(__DIR__ . '/../config/conexao.php');
require_once(__DIR__ . '/../models/calendario_aula.php');


if (!isset($_SESSION['idusuario']) || ($_SESSION['papel'] !== 'aluno' && $_SESSION['papel'] !== 'admin')) {
    header('Location: ../views/login.php');
    exit();
}

$idusuario = $_SESSION['idusuario'];
$conn = Conexao::conectar(); 

// Estas variáveis são mais para a homeController.php, mas mantidas aqui se for um controlador compartilhado.
$nomeAluno = 'Aluno'; 
$idaluno = null; 
$proximaAula = null; 
$ultimosMateriais = []; 
$erroHome = null; 

// Pega o ID do usuário e o papel da sessão.
$idusuario = $_SESSION['idusuario'];
$papelUsuario = $_SESSION['papel'];
error_log("DEBUG: idusuario logado: " . $idusuario . ", Papel: " . $papelUsuario);

$year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');
$month = isset($_GET['month']) ? (int)$_GET['month'] : date('m');

if ($month < 1 || $month > 12) {
    $month = date('m');
}

// Calcula o dia da semana do primeiro dia do mês e o número total de dias no mês.
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

// Buscar eventos
try {
    // Chama 'getEventsAlunos' do modelo para buscar eventos no BD, passando o ano e o mês
    $events = $calendarioAulaModel->getEventsAlunos($idusuario, $year, $month);
    error_log("DEBUG: getEventsAlunos executado com sucesso. " . count($events) . " eventos encontrados para " . $monthNames[(int)$month] . "/" . $year . ".");
} catch (PDOException $e) {
    error_log("ERRO PDO em calendarioController ao buscar eventos: " . $e->getMessage());
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest' || isset($_GET['ajax'])) {
        http_response_code(500); 
        header('Content-Type: application/json'); 
        echo json_encode(['error' => 'Erro interno do servidor ao carregar eventos do calendário.', 'details' => $e->getMessage()]);
        exit; 
    } else {
        die("Erro ao carregar dados do calendário. Por favor, tente novamente mais tarde.");
    }
} catch (Exception $e) {
    error_log("ERRO GERAL em calendarioController ao buscar eventos: " . $e->getMessage());
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest' || isset($_GET['ajax'])) {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Ocorreu um erro inesperado do servidor ao carregar eventos.', 'details' => $e->getMessage()]);
        exit;
    } else {
        die("Ocorreu um erro inesperado. Por favor, tente novamente mais tarde.");
    }
}

// Responde com JSON contendo os eventos.
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest' || isset($_GET['ajax'])) {
    header('Content-Type: application/json');
    echo json_encode(['events' => $events]); // Envia todos os eventos para o frontend via AJAX
    error_log("DEBUG: Resposta AJAX enviada com " . count($events) . " eventos.");
    exit; 
}

include __DIR__ . '/../views/student/class.php';
exit; 
