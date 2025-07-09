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
            echo "Apenas usuários autorizados podem cadastrar tipos de material.";
            exit;
        }
        $ok = $tipoMaterial->cadastrar($_POST['descricao']);
        echo $ok ? "Tipo de material cadastrado com sucesso!" : "Erro ao cadastrar tipo de material.";
        break;

    case 'alterar':
        if (!temPermissao()) {
            http_response_code(403);
            echo "Apenas usuários autorizados podem alterar tipos de material.";
            exit;
        }
        $ok = $tipoMaterial->alterar($_POST['idtipo_material'], $_POST['descricao']);
        echo $ok ? "Tipo de material alterado com sucesso!" : "Erro ao alterar tipo de material.";
        break;

    case 'excluir':
        if (!temPermissao()) {
            http_response_code(403);
            echo "Apenas usuários autorizados podem excluir tipos de material.";
            exit;
        }
        if (!isset($_GET['idtipo_material'])) {
            http_response_code(400);
            echo "ID do tipo de material não informado.";
            exit;
        }
        $ok = $tipoMaterial->excluir($_GET['idtipo_material']);
        echo $ok ? "Tipo de material excluído com sucesso!" : "Erro ao excluir tipo de material.";
        break;

    case 'listarTodos':
        echo json_encode($tipoMaterial->listarTodos());
        break;

    case 'listarId':
        if (!isset($_GET['idtipo_material'])) {
            http_response_code(400);
            echo "ID do tipo de material não informado.";
            exit;
        }
        echo json_encode($tipoMaterial->listarId($_GET['idtipo_material']));
        break;

    default:
        echo "Ação inválida.";
        break;
}
