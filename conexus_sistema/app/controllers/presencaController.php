<?php
session_start();
require_once "../model/Presenca.php";

$presenca = new Presenca();

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
            echo "Acesso negado. Apenas usuários autorizados podem cadastrar presença.";
            exit;
        }

        if (!isset($_POST['idaula'], $_POST['idaluno_turma'], $_POST['presente'])) {
            http_response_code(400);
            echo "Campos obrigatórios não informados.";
            exit;
        }

        $ok = $presenca->cadastrar(
            $_POST['idaula'],
            $_POST['idaluno_turma'],
            $_POST['presente'],
            $_POST['observacao'] ?? null
        );

        echo $ok ? "Presença cadastrada com sucesso!" : "Erro ao cadastrar presença.";
        break;

    case 'alterar':
        if (!temPermissao()) {
            http_response_code(403);
            echo "Acesso negado. Apenas usuários autorizados podem alterar presença.";
            exit;
        }

        if (!isset($_POST['idpresenca'], $_POST['idaula'], $_POST['idaluno_turma'], $_POST['presente'])) {
            http_response_code(400);
            echo "Campos obrigatórios não informados.";
            exit;
        }

        $ok = $presenca->alterar(
            $_POST['idpresenca'],
            $_POST['idaula'],
            $_POST['idaluno_turma'],
            $_POST['presente'],
            $_POST['observacao'] ?? null
        );

        echo $ok ? "Presença alterada com sucesso!" : "Erro ao alterar presença.";
        break;

    case 'excluir':
        if (!temPermissao()) {
            http_response_code(403);
            echo "Apenas usuários autorizados podem excluir presença.";
            exit;
        }

        if (!isset($_GET['idpresenca'])) {
            http_response_code(400);
            echo "ID da presença não informado.";
            exit;
        }

        $ok = $presenca->excluir($_GET['idpresenca']);
        echo $ok ? "Presença excluída com sucesso!" : "Erro ao excluir presença.";
        break;

    case 'listarTodos':
        echo json_encode($presenca->listarTodos());
        break;

    case 'listarId':
        if (!isset($_GET['idpresenca'])) {
            http_response_code(400);
            echo "ID da presença não informado.";
            exit;
        }

        echo json_encode($presenca->listarId($_GET['idpresenca']));
        break;

    default:
        echo "Ação inválida.";
        break;
}
