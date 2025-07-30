<?php
session_start();

require_once "../models/aluno_turma.php";

$alunoTurma = new AlunoTurma();

function estaLogado() {
    return isset($_SESSION['idusuario']);
}

function temPermissao() {
    return isset($_SESSION['papel']) && in_array($_SESSION['papel'], ['admin', 'funcionario', 'professor']);
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
    case 'vincular':
        if (!temPermissao()) {
            http_response_code(403);
            echo "Acesso negado. Apenas usuários autorizados podem matricular alunos em turmas.";
            exit;
        }

        $idaluno = $_GET['idaluno'] ?? null;
        $idturma = $_GET['idturma'] ?? null;
        //$data_matricula = $_POST['data_matricula'] ?? null;

        if (!isset($_GET['idaluno'], $_GET['idturma'])) {
            echo "Erro: ID do aluno e ID da turma são obrigatórios.";
        exit;
}


        $alunoTurma->cadastrar($idaluno, $idturma);
        //"Location: ../views/admin/list_student.php?idturma=$idturma&vinculado=ok"
        header("Location: ../views/admin/list_students.php?idturma=" . $idturma . "&vinculado=ok");
            exit;

    case 'alterar':
        if (!temPermissao()) {
            http_response_code(403);
            echo "Acesso negado. Apenas usuários autorizados podem alterar matrícula.";
            exit;
        }

        $idaluno_turma = $_POST['idaluno_turma'] ?? null;
        $idaluno = $_GET['idaluno'] ?? null;
        $idturma = $_GET['idturma'] ?? null;
        $data_matricula = $_POST['data_matricula'] ?? null;

        if (!$idaluno_turma || !$idaluno || !$idturma || !$data_matricula) {
            echo "Erro: Todos os campos são obrigatórios para alteração.";
            exit;
        }

        $alunoTurma->alterar($idaluno_turma, $idaluno, $idturma, $data_matricula);

        header("Location: ../views/components/sucess.php?alterar=ok");
            exit;

    case 'excluir':
        if (!temPermissao()) {
            http_response_code(403);
            echo "Acesso negado.";
            exit;
        }

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
