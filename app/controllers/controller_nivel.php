<?php
session_start();
require_once "../model/Nivel.php";

$nivel = new Nivel();

function estaLogado() {
    return isset($_SESSION['idusuario']);
}

function temPermissao() {
    return isset($_SESSION['papel']) && in_array($_SESSION['papel'], ['admin', 'funcionario', 'professor']);
}

if (!estaLogado()) {
    http_response_code(401);
    echo "Acesso negado.";
    exit;
}

if (isset($_GET['acao'])) {
    $acao = $_GET['acao'];

    switch ($acao) {
        case 'cadastrar':
            if (!temPermissao()) {
                http_response_code(403);
                echo "Sem permissão para cadastrar.";
                exit;
            }
            $nivel->cadastrar($_POST['descricao']);
            echo "Nível cadastrado com sucesso!";
            break;

        case 'alterar':
            if (!temPermissao()) {
                http_response_code(403);
                echo "Sem permissão para alterar.";
                exit;
            }
            $nivel->alterar($_POST['idnivel'], $_POST['descricao']);
            echo "Nível alterado com sucesso!";
            break;

        case 'excluir':
            if (!temPermissao()) {
                http_response_code(403);
                echo "Sem permissão para excluir.";
                exit;
            }
            $nivel->excluir($_GET['idnivel']);
            echo "Nível excluído com sucesso!";
            break;

        case 'listarTodos':
            echo json_encode($nivel->listarTodos());
            break;

        case 'listarId':
            echo json_encode($nivel->listarId($_GET['idnivel']));
            break;

        default:
            echo "Ação inválida.";
            break;
    }
} else {
    echo "Nenhuma ação definida.";
}
