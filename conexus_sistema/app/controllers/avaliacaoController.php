<?php
session_start();
require_once "../model/Avaliacao.php";

$avaliacao = new Avaliacao();

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
    case 'cadastrar':
        if (!temPermissao()) {
            http_response_code(403);
            echo "Apenas usuários autorizados podem cadastrar avaliações.";
            exit;
        }

        $ok = $avaliacao->cadastrar(
            $_POST['idaluno_turma'],
            $_POST['descricao'],
            $_POST['titulo'],
            $_POST['data_avaliacao'],
            $_POST['nota'],
            $_POST['peso'] ?? 1.0,
            $_POST['observacao'] ?? null
        );

        header("Location: ../views/admin/sucesso.php");
        exit;

    case 'alterar':
        if (!temPermissao()) {
            http_response_code(403);
            echo "Apenas usuários autorizados podem alterar avaliações.";
            exit;
        }

        $ok = $avaliacao->alterar(
            $_POST['idavaliacao'],
            $_POST['idaluno_turma'],
            $_POST['descricao'],
            $_POST['titulo'],
            $_POST['data_avaliacao'],
            $_POST['nota'],
            $_POST['peso'] ?? 1.0,
            $_POST['observacao'] ?? null
        );

        header("Location: ../views/admin/sucesso.php");
        exit;

    case 'excluir':
        if (!temPermissao()) {
            http_response_code(403);
            echo "Apenas usuários autorizados podem excluir avaliações.";
            exit;
        }

        if (!isset($_GET['idavaliacao'])) {
            http_response_code(400);
            echo "ID da avaliação não informado.";
            exit;
        }

        $ok = $avaliacao->excluir($_GET['idavaliacao']);

        //echo $ok ? "Avaliação excluída com sucesso!" : "Erro ao excluir avaliação.";
        //break;
        header("Location: ../views/admin/sucesso.php");
        exit;

    case 'listarTodos':
        echo json_encode($avaliacao->listarTodos());
        break;

    case 'listarId':
        if (!isset($_GET['idavaliacao'])) {
            http_response_code(400);
            echo "ID da avaliação não informado.";
            exit;
        }

        echo json_encode($avaliacao->listarId($_GET['idavaliacao']));
        break;

    default:
        echo "Ação inválida.";
        break;
}
