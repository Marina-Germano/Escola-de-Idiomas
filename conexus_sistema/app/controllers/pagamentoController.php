<?php

session_start();

require_once(__DIR__ . '/../config/conexao.php');
require_once(__DIR__ . '/../models/pagamento.php');


if (!isset($_SESSION['idusuario']) || ($_SESSION['papel'] !== 'aluno' && $_SESSION['papel'] !== 'admin')) {
    header('Location: /escola-de-idiomas/conexus_sistema/app/views/login.php');
    exit();
}

$idusuario = $_SESSION['idusuario'];

$idalunoLogado = null;
try {
    $pdo = Conexao::conectar();
    $stmt = $pdo->prepare("SELECT idaluno FROM aluno WHERE idusuario = :idusuario");
    $stmt->bindParam(':idusuario', $idusuario, PDO::PARAM_INT);
    $stmt->execute();
    $resultadoAluno = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($resultadoAluno) {
        $idalunoLogado = $resultadoAluno['idaluno'];
    } else {
        error_log("ERRO: idusuario " . $idusuario . " não encontrado na tabela aluno.");
        header('Location: /escola-de-idiomasonexus_sistema/app/views/login.php'); 
        exit;
    }
} catch (PDOException $e) {
    error_log("ERRO PDO ao buscar idaluno: " . $e->getMessage());
    header('Location: /escola-de-idiomasonexus_sistema/app/views/erro.php'); 
    exit;
}

$erroFinanceiro = '';
$historicoPagamentos = [];
$pagamentosPendentes = [];
$totalPago = 0.00;
$totalPendente = 0.00;
$cursoSelecionado = '';

$mapaCursosIdiomas = [
    'ingles' => 1,
    'espanhol' => 2,
    'frances' => 3
];

$cursosDoAluno = [];
if (isset($_SESSION['idusuario']) && $_SESSION['papel'] === 'aluno') {
    $cursosDoAluno = [
        ['id_curso' => 'ingles', 'nome_curso' => 'Inglês'],
        ['id_curso' => 'espanhol', 'nome_curso' => 'Espanhol'],
    ];
}

if (isset($_POST['curso'])) {
    $cursoSelecionado = strtolower($_POST['curso']);

    if ($cursoSelecionado !== 'default' && isset($mapaCursosIdiomas[$cursoSelecionado])) {
        $id_idioma_selecionado = $mapaCursosIdiomas[$cursoSelecionado];

        $pagamentoModel = new Pagamento();

        try {
            $totalPago = $pagamentoModel->getTotalPago($idalunoLogado, $id_idioma_selecionado);
            $totalPendente = $pagamentoModel->getTotalPendente($idalunoLogado, $id_idioma_selecionado);
            $historicoPagamentos = $pagamentoModel->getHistoricoPagamentos($idalunoLogado, $id_idioma_selecionado);
            $pagamentosPendentes = $pagamentoModel->getPagamentosPendentes($idalunoLogado, $id_idioma_selecionado);

        } catch (Exception $e) {
            error_log("Erro inesperado no controlador pagamento: " . $e->getMessage());
            $erroFinanceiro = "Ocorreu um erro ao buscar os dados financeiros. Tente novamente mais tarde.";
            $totalPago = 0.00;
            $totalPendente = 0.00;
            $historicoPagamentos = [];
            $pagamentosPendentes = [];
        }

    } else {
        $erroFinanceiro = "Por favor, selecione um curso válido para ver os detalhes financeiros.";
        $totalPago = 0.00;
        $totalPendente = 0.00;
        $historicoPagamentos = [];
        $pagamentosPendentes = [];
    }
}

include __DIR__ . '/../views/student/financial.php';
