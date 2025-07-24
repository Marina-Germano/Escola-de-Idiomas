<?php
session_start();

require_once(__DIR__ . '/../config/conexao.php');
require_once(__DIR__ . '/../models/aluno.php');
require_once(__DIR__ . '/../models/usuario.php');
require_once(__DIR__ . '/../models/calendario_aula.php');
require_once(__DIR__ . '/../models/material.php');


if (!isset($_SESSION['idusuario']) || ($_SESSION['papel'] !== 'aluno' && $_SESSION['papel'] !== 'admin')) {
    header('Location: ../views/login.php');
    exit();
}

$idusuario = $_SESSION['idusuario'];
$conn = Conexao::conectar();

$nomeAluno = 'Aluno';
$idaluno = null;
$proximaAula = null;
$ultimosMateriais = [];
$erroHome = null;

try {

    $alunoModel = new Aluno();
    $usuarioModel = new Usuario();
    $calendarioAulaModel = new CalendarioAula();
    $materialModel = new Material();

    // buscar idaluno usando
    $idaluno = $alunoModel->buscarIdPorUsuario($idusuario);

    // sse  for encontrado busca o nome do usuário associado a ele
    if ($idaluno) {
        $usuarioInfo = $usuarioModel->listarId($idusuario);
        if ($usuarioInfo) {
            $nomeAluno = $usuarioInfo['nome'];
        }
    } else {
        $erroHome = "ID do aluno não encontrado para este usuário. Verifique se o usuário está associado a um aluno.";
        error_log("ERRO: homeController.php - " . $erroHome);
    }

    // buscar pxm aula
    if ($idaluno) {
        $proximaAula = $calendarioAulaModel->getProximaAulaPorAluno($idaluno);
    }

    // proximo 6 materias
    if ($idaluno) {
        $ultimosMateriais = $materialModel->getUltimosMateriaisPorAluno($idaluno, 6);
    }

} catch (PDOException $e) {
    error_log("ERRO PDO em homeController.php: " . $e->getMessage());
    $erroHome = "Ocorreu um erro ao carregar os dados. Por favor, tente novamente mais tarde.";
} catch (Exception $e) {
    error_log("ERRO Geral em homeController.php: " . $e->getMessage());
    $erroHome = "Ocorreu um erro inesperado ao carregar os dados. Por favor, tente novamente mais tarde.";
}

require_once(__DIR__ . '/../views/student/home.php');
?>
