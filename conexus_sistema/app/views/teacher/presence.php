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
    $presencaModel->registrarPresenca($idaluno, $presente, $data);
}

header("Location: list_student.php");
exit;
