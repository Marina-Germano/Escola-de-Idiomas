<?php
session_start();
require_once "../model/AlunoTurma.php";

$alunoTurma = new AlunoTurma();

function estaLogado() {
    return isset($_SESSION['idusuario']);
}

function temPermissao() {
    return isset($_SESSION['papel']) && in_array($_SESSION['papel'], ['admin', 'funcionario']);
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

switch ($acao) {
    case 'cadastrar':
        if (!temPermissao()) {
            http_response_code(403);
            echo "Acesso negado. Apenas usuários autorizados podem matricular alunos em turmas.";
            exit;
        }

        $idaluno = $_POST['idaluno'] ?? null;
        $idturma = $_POST['idturma'] ?? null;
        $data_matricula = $_POST['data_matricula'] ?? null;

        if (!$idaluno || !$idturma) {
            echo "Erro: ID do aluno e ID da turma são obrigatórios.";
            exit;
        }

        $alunoTurma->cadastrar($idaluno, $idturma, $data_matricula);

        header("Location: ../views/admin/sucesso.php");
        exit;

    case 'alterar':
        if (!temPermissao()) {
            http_response_code(403);
            echo "Acesso negado. Apenas usuários autorizados podem alterar matrícula.";
            exit;
        }

        $idaluno_turma = $_POST['idaluno_turma'] ?? null;
        $idaluno = $_POST['idaluno'] ?? null;
        $idturma = $_POST['idturma'] ?? null;
        $data_matricula = $_POST['data_matricula'] ?? null;

        if (!$idaluno_turma || !$idaluno || !$idturma || !$data_matricula) {
            echo "Erro: Todos os campos são obrigatórios para alteração.";
            exit;
        }

        $alunoTurma->alterar($idaluno_turma, $idaluno, $idturma, $data_matricula);

        header("Location: ../views/admin/sucesso.php");
        exit;

    case 'excluir':
        if (!temPermissao()) {
            http_response_code(403);
            
            echo "Acesso negado. Apenas usuários autorizados podem excluir matrícula.";
            exit;
        }

        $idaluno_turma = $_GET['idaluno_turma'] ?? null;

        if (!$idaluno_turma) {
            echo "Erro: ID da matrícula não informado.";
            exit;
        }

        $alunoTurma->excluir($idaluno_turma);

        header("Location: ../views/admin/sucesso.php");
        exit;

    case 'listarTodos':
        echo json_encode($alunoTurma->listarTodos($idturma));
        break;

    case 'listarId':
        $idaluno_turma = $_GET['idaluno_turma'] ?? null;

        if (!$idaluno_turma) {
            echo "Erro: ID da matrícula não informado.";
            exit;
        }

        echo json_encode($alunoTurma->listarId($idaluno_turma));
        break;

    default:
        echo "Ação inválida.";
        break;
}
