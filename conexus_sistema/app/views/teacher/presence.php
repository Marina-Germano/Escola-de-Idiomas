<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "../../models/presenca.php";
require_once "../../models/aluno.php";

$presencaModel = new Presenca();
$alunoModel = new Aluno();


$faltas = isset($_POST['faltas']) ? $_POST['faltas'] : []; // Array de idaluno com falta
$data = date('Y-m-d');

// Lista todos os alunos para registrar presença/falta
$alunos = $alunoModel->listarTodos(); // ou filtrar por turma, se necessário

foreach ($alunos as $aluno) {
    $idaluno = $aluno['idaluno'];
    $presente = in_array($idaluno, $faltas) ? 0 : 1;

    // 🔍 buscar idaluno_turma
    $conn = Conexao::conectar();
    $stmt = $conn->prepare("SELECT idaluno_turma FROM aluno_turma WHERE idaluno = ? LIMIT 1");
    $stmt->execute([$idaluno]);
    $idaluno_turma = $stmt->fetchColumn();

    if ($idaluno_turma) {
        $presencaModel->registrarPresenca($idaluno_turma, $presente);
    }
}

header("Location: list_student.php");
exit;
?>