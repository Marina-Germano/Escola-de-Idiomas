<?php
session_start();
require_once "../model/TipoMaterial.php";

$tipoMaterial = new TipoMaterial();

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
            $tipoMaterial->cadastrar($_POST['tipo']);
            echo "Tipo de material cadastrado!";
            break;

        case 'alterar':
            if (!temPermissao()) {
                http_response_code(403);
                echo "Sem permissão para alterar.";
                exit;
            }
            $tipoMaterial->alterar($_POST['idtipo_material'], $_POST['tipo']);
            echo "Tipo de material alterado!";
            break;

        case 'excluir':
            if (!temPermissao()) {
                http_response_code(403);
                echo "Sem permissão para excluir.";
                exit;
            }
            $tipoMaterial->excluir($_GET['idtipo_material']);
            echo "Tipo de material excluído!";
            break;

        case 'listarTodos':
            echo json_encode($tipoMaterial->listarTodos());
            break;

        case 'listarId':
            echo json_encode($tipoMaterial->listarId($_GET['idtipo_material']));
            break;

        default:
            echo "Ação inválida.";
            break;
    }
} else {
    echo "Nenhuma ação definida.";
}
