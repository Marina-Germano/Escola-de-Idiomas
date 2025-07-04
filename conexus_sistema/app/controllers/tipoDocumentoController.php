<?php
session_start();
require_once "../model/TipoDocumento.php";

$tipoDocumento = new TipoDocumento();

function estaLogado() {
    return isset($_SESSION['idusuario']);
}

function temPermissao() {
    return isset($_SESSION['papel']) && in_array($_SESSION['papel'], ['admin', 'funcionario']);
}

if (!estaLogado()) {
    http_response_code(401);
    echo "Acesso negado. Faça login.";
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
            $tipoDocumento->cadastrar($_POST['titulo_documento']);
            echo "Tipo de documento cadastrado!";
            break;

        case 'alterar':
            if (!temPermissao()) {
                http_response_code(403);
                echo "Sem permissão para alterar.";
                exit;
            }
            $tipoDocumento->alterar($_POST['idtipo_documento'], $_POST['titulo_documento']);
            echo "Tipo de documento alterado!";
            break;

        case 'excluir':
            if (!temPermissao()) {
                http_response_code(403);
                echo "Sem permissão para excluir.";
                exit;
            }
            $tipoDocumento->excluir($_GET['idtipo_documento']);
            echo "Tipo de documento excluído!";
            break;

        case 'listarTodos':
            echo json_encode($tipoDocumento->listarTodos());
            break;

        case 'listarId':
            echo json_encode($tipoDocumento->listarId($_GET['idtipo_documento']));
            break;

        default:
            echo "Ação inválida.";
            break;
    }
} else {
    echo "Nenhuma ação definida.";
}
