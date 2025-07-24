<?php
session_start();
require_once "../models/tipo_documento.php";

$tipoDocumento = new TipoDocumento();

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
            echo "Apenas usuários autorizados podem cadastrar tipos de documentos.";
            exit;
        }

        $descricao = $_POST['descricao'] ?? null;

        if (!$descricao) {
            echo "Erro: descrição obrigatória.";
            exit;
        }

        $tipoDocumento->cadastrar($descricao);
        header("Location: ../views/components/sucesso.php?cadastrar=ok");
        exit;

    case 'alterar':
        if (!temPermissao()) {
            http_response_code(403);
            echo "Apenas usuários autorizados podem alterar tipos de documentos.";
            exit;
        }

        $id = $_POST['idtipo_documento'] ?? null;
        $descricao = $_POST['descricao'] ?? null;

        if (!$id || !$descricao) {
            echo "Erro: ID e descrição obrigatórios.";
            exit;
        }

        $tipoDocumento->alterar($id, $descricao);
        header("Location: ../views/components/sucesso.php?alterar=ok");
        exit;

    case 'excluir':
        if (!temPermissao()) {
            http_response_code(403);
            echo "Apenas usuários autorizados podem excluir tipos de documentos.";
            exit;
        }

        $id = $_GET['idtipo_documento'] ?? null;

        if (!$id) {
            echo "Erro: ID não informado.";
            exit;
        }

        $tipoDocumento->excluir($id);
        header("Location: ../views/components/sucesso.php?excluir=ok");
        exit;

    case 'listarTodos':
        echo json_encode($tipoDocumento->listarTodos());
        break;

    case 'listarId':
        $id = $_GET['idtipo_documento'] ?? null;

        if (!$id) {
            echo "Erro: ID não informado.";
            exit;
        }

        echo json_encode($tipoDocumento->listarId($id));
        break;

    default:
        echo "Ação inválida.";
        break;
}
